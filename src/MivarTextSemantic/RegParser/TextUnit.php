<?php


namespace Aot\MivarTextSemantic\RegParser;

/**
 * class TextUnit
 *
 * @brief Базовый класс для описания блоков текста
 * @author Елисеев Д.В.
 *
 */


class TextUnit
{
    public $index = 0;
    /**< индекс блока в массиве */
    public $text = "";
    /**< текст блока */
    protected $items = array();
    /**< массив блоков нижнего уровня */
    protected $array_reg = array();
    /**< массив регулярок для разбиения на блоки нижнего уровня */

    /**
     * @brief Конструктор класса
     * @param $index - индекс текстового блока в массиве
     * @param $text - текст блока
     */

    public function __construct(
        $index = 0,
        $text = ""
    ) {
        $this->index = (is_integer($index)) ? $index : 0;
        $this->text = (is_string($text)) ? $text : "";
        $this->parse_text($this->text);
    }

    /**
     * @brief Метод для парсинга текста по регулярным выражениям
     *
     * @param $text - текст
     * @return ссылка на объект
     */

    public function parse_text($txt)
    {

        if ($txt) {
            $this->text = trim($txt);
            $this->items = array();
            $this->parse_text_unit($this, $this->array_reg);
        }
        return $this;

    }

    /**
     * @brief Метод разбивки текста на блоки
     *
     * @param &$text_unit - элемент текста
     * @param $array_reg_parts - регулярок для поиска
     * @param $name_unit - название блока текста
     * @return $text_unit массив - распарсенный текст
     */

    protected function parse_text_unit($obj_text_unit, $array_reg_parts)
    {
        $text = $obj_text_unit->text;
        mb_regex_encoding("utf-8");
        mb_ereg_search_init($text);
        $poz = 0;
        reset($array_reg_parts);
        $lastReg = key($array_reg_parts);
        $lastMinPoz = null;
        $length = strlen($text);
        //echo $length."<br>";
        while (($length > $poz) && $minPoz = $this->minPozReg($array_reg_parts, $text, $poz)) {
            /*echo "<pre>";
            print_r($minPoz);
            echo "</pre>";*/
            $minPoz["rezText"] = trim($minPoz["rezText"]);
            $obj_text_unit->$array_reg_parts[$lastReg]($minPoz, $lastMinPoz);
            $poz = $minPoz["poz"];
            $lastReg = $minPoz["reg"];
            $lastMinPoz = $minPoz;
            mb_ereg_search_init($text);
        }
        if ($length > $poz) {
            $minPoz["rezText"] = trim(mb_strcut($text, $poz));
        } else {
            $minPoz["rezText"] = "";
        }
        $obj_text_unit->$array_reg_parts[$lastReg]($minPoz, $lastMinPoz);
        return $obj_text_unit;
    }

    /**
     * @brief Определяет максимальное и первое вхождение регулярки в тексте
     *
     * @param $arrReg - массив регулярок
     * @param $text - текст
     * @param $startPoz - начальная позиция
     * @return $text_unit массив - массив найденных шаблонов в тексте и регулярки, которая сработала
     */

    protected function minPozReg($arrReg, $text, $startPoz)
    {
        $minPoz = -1;
        $reg = "";
        $regText = "";
        $rezText = "";
        $rez = false;
        $lenReg = -1;
        //echo get_class($this)."<br>".$startPoz."<br>".$text."<br>";
        foreach ($arrReg as $stRegKey => $stReg) {
            //if (get_class($this) != 'Text' || $startPoz != 0)
            //echo "start_poz: ".$startPoz."<br>";
            //echo "проверка: ".mb_ereg_search_setpos ($startPoz)."<br>";
            mb_ereg_search_setpos($startPoz);
            $match = mb_ereg_search_pos($stRegKey);
            if ($match) {
                $i = mb_ereg_search_getpos();
                if ($minPoz == -1 || $minPoz >= $match[0]) {
                    $minPoz = $match[0];
                    $lenReg = $match[1];
                    $reg = $stRegKey;
                    $rez = true;
                    $poz = $i;
                    $regText = mb_strcut($text, $match[0], $match[1]);
                    $rezText = mb_strcut($text, $startPoz, $match[0] - $startPoz);
                }
            }
        }
        if ($rez) {
            return array("reg" => $reg, "poz" => $poz, "regText" => $regText, "rezText" => $rezText);
        } else {
            return false;
        }
    }

    /**
     * @brief Получаем список слов из текста
     *
     * @return массив слов
     */

    public function get_text_words()
    {
        $result = array();
        foreach ($this->items as $item) {
            $result = array_merge($result, $item->get_text_words());
        }
        return $result;
    }

    /**
     * @brief Получаем список предложений из текста
     *
     * @return массив предложений
     */

    public function get_sentences()
    {
        $result = array();
        foreach ($this->items as $item) {
            $result = array_merge($result, $item->get_sentences());
        }
        return $result;
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