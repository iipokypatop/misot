<?php

namespace Aot\MivarTextSemantic\SyntaxParser;

/**
 *
 * class SyntaxRule
 *
 * @brief Абстрактный класс, который содержит все общие функции по работе с синтаксическими правиласи
 *
 */
abstract class SyntaxRule
{
    public $wdw;
    /**< Пространство wdw предложения */
    public $find_points_wdw;
    /**< Найденный точки для обработки правилом */
    public $syntax_model_rule;
    /**< Синтаксическая модель правила */
    public $error = "";
    /**< Ошибка в структуре предложения или при выполнении правила */
    public $sence_filter = false;
    /**< смысловой фильтр */
    public $train_system_mode = false;
    /**< режим обучения системы */
    public $syntax_db = null;
    /**< объект для работы с синтаксической БД шаблонов */

    /**
     * @brief Конструктор класса
     * @param $train_system_mode - режим обучения системы
     * @param $connection_string - строка соединения с БД
     */

    public function __construct($train_system_mode = false, $syntax_db = null)
    {
        $this->train_system_mode = $train_system_mode;
        $this->syntax_db = $syntax_db;
        /*$this->syntax_db = ($this->train_system_mode = $train_system_mode) ?
            new SyntaxDb : null;*/
    }

    /**
     * @brief Абстрактный метод анализа предложения на возможность запуска правила
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */

    abstract public function analyze($wdw);

    /**
     * @brief Абстрактный метод выполнения правила
     */

    abstract public function perfom();

    /**
     * @brief Абстрактный метод смысловой фильтрации полученных результатов (Если необходимо)
     */

    abstract public function sence_filter();

    /**
     * @brief связывание слов определённой связью
     * @param $point_space - синтаксическая модель предложения
     * @param $word1 - слово (главное, main)
     * @param $word2 - слово (зависимое, depend)
     * @param $uuid_rel - id отношение
     * @param - $id_sentence - id предложения
     * @return изменённая синтаксическая модель предложения
     */

    public function set_point_rel($point_space, $word1, $word2, $uuid_rel)
    {

        $save_w1 = true;
        $save_w2 = true;
        $word1->direction = 'x';
        $word2->direction = 'y';
        $word1->id_sentence = $word2->id_sentence = $word2->w->id_sentence;

        foreach ($point_space->get_space() as $point) {
            if (isset($point->Oz) && $point->Oz == $uuid_rel) {
                if ($point->kw == $word1->kw) {
                    $save_w1 = false;
                } else {
                    if ($point->kw == $word2->kw) {
                        $save_w2 = false;
                    }
                }
            }
        }
        if ($save_w1) {
            $point_space->add_point($word1->create_point_wdwOOz($word1->O, $word1->Oz, $word1->direction,
                $word1->w->id_sentence));
        }
        if ($save_w2) {
            $point_space->add_point($word2->create_point_wdwOOz($word2->O, $word2->Oz, $word2->direction,
                $word2->w->id_sentence));
        }
        //$this->view($point_space);
        return $point_space;
    }

    /**
     * @brief сравнение совпадающих параметров слов для согласования
     * @param $dict_word1 слово из словаря 1
     * @param $dict_word2 слово из словаря 2
     * @param $parameters - массив морфологических параметров для сравнения
     * @return возвращает булево значение true при равентсве параметров заданных марфологических признаков слов иначе false
     */

    public function compare_parameters($dict_word1, $dict_word2, $parameters)
    {
        foreach ($parameters as $param) {
            if (isset($dict_word1->parameters[$param])) {
                if (isset($dict_word2->parameters[$param])) {
                    foreach ($dict_word1->parameters[$param]->id_value_attr as $id_value) {
                        if (!in_array($id_value, $dict_word2->parameters[$param]->id_value_attr)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * @brief Поиск частей речи до слова в предложении
     * @param $key_wdw - ключ точки wdw для начала поиска
     * @param $find_ps - массив искомых частей речи
     * @param $stop_ps - массив частей речи, останавливающих поиск
     * @return возвращает булево значение true если раньше встретилась часть речи $find_ps, чем $stop_ps. Иначе false
     */

    public function find_ps_prev($kw, $find_ps = array(), $stop_ps = array())
    {
        $result = false;
        /*if ($key_wdw == 12) {
            $this->view($this->wdw[12]);
        }*/
        for ($i = $kw; $i >= 0; $i--) {
            foreach ($this->wdw->get_space_kw_item($i) as $key_wdw => $point_wdw) {
                foreach ($find_ps as $fps) {
                    if ($point_wdw->dw->check_parameter($fps, null, null)) {
                        $result = true;
                        break 3;
                    }
                }
                foreach ($stop_ps as $sps) {
                    if ($point_wdw->dw->check_parameter($sps, null, null)) {
                        break 3;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @brief Поиск частей речи после слова в предложении
     * @param $key_wdw - ключ точки wdw для начала поиска
     * @param $find_ps - массив искомых частей речи
     * @param $stop_ps - массив частей речи, останавливающих поиск
     * @return возвращает булево значение true если после встретилась часть речи $find_ps, а не $stop_ps. Иначе false
     */

    public function find_ps_next($kw, $find_ps = array(), $stop_ps = array())
    {
        $result = false;
        /*if ($key_wdw == 12) {
            $this->view($this->wdw[12]);
        }*/
        for ($i = $kw; $i < count($this->wdw->get_space_kw()); $i++) {
            foreach ($this->wdw->get_space_kw_item($i) as $key_wdw => $point_wdw) {
                foreach ($find_ps as $fps) {
                    if ($point_wdw->dw->check_parameter($fps, null, null)) {
                        $result = true;
                        break 3;
                    }
                }
                foreach ($stop_ps as $sps) {
                    if ($point_wdw->dw->check_parameter($sps, null, null)) {
                        break 3;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @autor Сараев Д.В.
     * @brief поиск между двумя словами
     * @param $kw - ключ точки wdw для начала поиска
     * @param $id_word_class - часть речи
     * @param $id_morph_attribute - морфологический признак
     * @param $id_value - значение морфологического признака
     * @return возвращает булево значение true, если между словами встретились ",", "-", "и"
     */
    public function find_ps_between($kw, $id_word_class, $id_morph_attribute, $id_value)
    {
        $result = false;

        for ($i = $kw; $i >= 0; $i--) {
            foreach ($this->wdw->get_space_kw_item($i) as $key_wdw => $point_wdw) {
                if (isset($point_wdw->dw)) {
                    if ($point_wdw->dw->check_parameter($id_word_class, $id_morph_attribute, $id_value)) {
                        break 2;
                    }
                }
            }
        }
        for ($j = $i; $j <= $kw; $j++) {
            foreach ($this->wdw->get_space_kw_item($j) as $key_wdw => $point_wdw) {
                //if (isset($point_wdw->w) && $this->check_word($point_wdw->w, array(",", "-", "и"))){
                if (isset($point_wdw->w) && $point_wdw->w->check_word(array(",", "-", "и"))) {
                    $result = true;
                    break 2;
                } else {
                    $result = false;
                }
            }
        }
        return $result;
    }

    /**
     * @autor Сараев Д.В.
     * @brief Проверка наличия в параметрах совпадеения по знаку препинания
     * @param $dictionary_word - w (слово с морфологическими признаками)
     * @param $stop - массив знаков препинания
     * @return возвращает булево значение true в случае присутствия в w указанных признаков, иначе false
     * public function check_word($dictionary_word, $stop = array()) {
     * if (isset($dictionary_word->word) && in_array($dictionary_word->word, $stop)) {
     * return true;
     * }
     * return false;
     * }*/

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
