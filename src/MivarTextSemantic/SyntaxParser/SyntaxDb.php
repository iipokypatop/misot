<?php

namespace Aot\MivarTextSemantic\SyntaxParser;


/**
 *
 * class SyntaxDb
 *
 * @brief Класс для работы с БД синтаксической модели
 * @author Елисеев Д.В.
 *
 */
class SyntaxDb
{
    private $dbconn = null;
    /**< соединения с БД */
    private $points_to_db = array();
    /**< массив точек отношений для записи в БД */
    private $context = 1;
    /**< контекст для поисков */

    /**
     * @brief Конструктор класса
     * @param $connection_string - строка соединения с БД
     * @param $context - контекст для работы
     */

    public function __construct($connection_string = DB_MIVAR_INTELLIGENCE, $context = 1)
    {
        $this->dbconn = ($connection_string) ? pg_connect($connection_string) : null;
        $this->points_to_db = array();
        $this->context = $context;
    }

    /**
     * @brief Выполнить запрос к базе
     * @param $sql -SQL запрос
     * @return результат выполнения запроса
     */

    protected function query($sql)
    {
        return pg_query($this->dbconn, $sql);
    }

    /**
     * @brief Добавляем в класс синтаксическое отношение для записи
     * @param $main_w - главное слово (string)
     * @param $depend_w - зависимое слово (string)
     * @param $uuid_relation - uuid синтаксического отношения ()
     * @param $alias_relation - название синтаксическое отношение ()
     * @return ссылку на объект
     */

    public function add_syntax_relation($main_w, $depend_w, $uuid_relation, $alias_relation)
    {
        $this->points_to_db[md5($main_w . $uuid_relation)][$depend_w] = array('main_w' => mb_strtolower($main_w, 'utf-8'),
            'depend_w' => mb_strtolower($depend_w, 'utf-8'),
            'uuid_relation' => $uuid_relation,
            'alias_relation' => $alias_relation);
        return $this;
    }

    /**
     * @brief Сохранение синтаксических правил points_to_db в базе
     * @param $context - контекст
     * @param $vl - виртуальная личность
     * @param $origin - источник данных
     * @return Количество записанных правил
     */

    public function save_points_to_db($context = 1, $vl = 1, $origin = "")
    {
        $result = 0;

        // формируем запрос к базе с целью обнаружения уже записанных связей

        $str_saved_rules = "";
        foreach ($this->points_to_db as $group) {
            $flag_group = true;
            foreach ($group as $depend_w => $relation) {
                $str_saved_rules .= "(" . check_string($relation['main_w'], $this->dbconn) . ", " . check_uuid($relation['uuid_relation']) . "::uuid, " . check_string($relation['alias_relation'], $this->dbconn) . ", " . check_string($relation['depend_w'], $this->dbconn) . "), ";
            }
        }
        $saved_rules = array();
        if ($str_saved_rules) {
            $strQuery = "	SELECT	enter_rules.*,
						wooz_space_x.uuid_oz,
						wooz_space_x.word as main_w_db,
						wooz_space_y.word as depend_w_db
					FROM	(VALUES " . substr($str_saved_rules, 0, -2) . ") AS enter_rules (main_w, uuid_relation, alias_relation, depend_w)
					LEFT JOIN	wooz_space as wooz_space_x
					ON	enter_rules.main_w = wooz_space_x.word AND
						enter_rules.uuid_relation = wooz_space_x.uuid_o AND
						wooz_space_x.direction = 'x' AND
						wooz_space_x.context = {$this->context}
					LEFT JOIN	wooz_space as wooz_space_y
					ON	enter_rules.depend_w = wooz_space_y.word AND
						wooz_space_x.uuid_oz = wooz_space_y.uuid_oz AND
						wooz_space_y.direction = 'y' AND
						wooz_space_y.context = {$this->context};
			";
            //$saved_rules = pg_fetch_all($this->query($strQuery));
            //$this->view($strQuery);
            $res = $this->query($strQuery);
            while ($item = pg_fetch_assoc($res)) {
                $saved_rules[$item['main_w'] . $item['uuid_relation']][] = $item;
            }
        }

        $strQuery = "";
        $this->query("BEGIN;");
        foreach ($saved_rules as $depend_rules) {

            // Записываем главное слово с отношением

            if ($depend_rules && isset($depend_rules[0]['uuid_oz']) && $depend_rules[0]['uuid_oz']) {
                $oz = $depend_rules[0]['uuid_oz'];
            } else {
                $oz = $this->generate_uuid();
                $strQuery .= "	INSERT INTO	wooz_space(
									word,
									uuid_o,
									uuid_o_alias,
									uuid_oz,
									direction,
									context, 
									vl,
									origin)
						VALUES 		(" . check_string($depend_rules[0]['main_w'], $this->dbconn) . ",
								" . check_uuid($depend_rules[0]['uuid_relation']) . ",
								" . check_string($depend_rules[0]['alias_relation'], $this->dbconn) . ",
								" . check_uuid($oz) . ",
								'x',
								" . check_numeric($context) . ",
								" . check_numeric($vl) . ",
								" . check_string($origin, $this->dbconn) . ");
				";
                $result++;
            }
            foreach ($depend_rules as $rule) {
                if (!(isset($rule['depend_w_db']) && $rule['depend_w_db'])) {
                    $strQuery .= "	INSERT INTO	wooz_space(
										word,
										uuid_o,
										uuid_o_alias,
										uuid_oz,
										direction,
										context, 
										vl,
										origin)
							VALUES 		(" . check_string($rule['depend_w'], $this->dbconn) . ",
									" . check_uuid($rule['uuid_relation']) . ",
									" . check_string($rule['alias_relation'], $this->dbconn) . ",
									" . check_uuid($oz) . ",
									'y',
									" . check_numeric($context) . ",
									" . check_numeric($vl) . ",
									" . check_string($origin, $this->dbconn) . ");
					";
                    $result++;
                }
            }
        }
        //$this->view($strQuery);
        $this->query($strQuery . "COMMIT;");
        //$this->view($saved_rules);
        return $result;
    }

    /**
     * @brief генерация uuid на стороне базы
     * @return uuid
     */

    protected function generate_uuid()
    {
        $uuid_oz = null;
        $res = $this->query("SELECT uuid_generate_v1() AS uuid_oz;");
        if ($uuid = pg_fetch_assoc($res)) {
            $uuid_oz = $uuid['uuid_oz'];
        }
        return $uuid_oz;
    }

    /**
     * @brief Очистка массива точек для записи
     */

    public function clear_points()
    {
        $this->points_to_db = array();
    }

    /**
     * @brief Отладка. Вывод на экран и завершение работы
     */

    protected function view($data)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre/>";
        die;
    }
}

?>
