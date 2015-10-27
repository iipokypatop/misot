<?php

namespace Aot\MivarTextSemantic\RegParser\RegClasses;

use Aot\MivarTextSemantic\RegParser\TextUnit;


//use reg_classes;
/**
 * class Word
 *
 * @brief Класс для работы со словом
 * @author Елисеев Д.В.
 *
 */
// new reg_classes/Word

class Word extends TextUnit
{

    public $data = false;
    /**< слово является датой */
    public $name_fio = false;
    /**< слово является ФИО */
    public $stop = false;
    /**< слово является знаком препинания */
    public $cut = false;
    /**< слово является сокращением */
    public $abbr = false;
    /**< слово является аббревиатурой */

    /**
     * @brief Конструктор класса
     * @param $index - индекс текстового блока в массиве
     * @param $text - текст блока
     * @param $flags - массив флагов слова (имя флага => true, если он есть)
     */

    public function __construct($index = 0,
                                $text = "",
                                $flags = array())
    {
        if (is_array($flags) && $flags) {
            foreach ($flags as $key => $val) {
                if (property_exists($this, $key))
                    $this->$key = $flags[$key];
            }
        }
        parent::__construct($index,
            $text);
    }

    /**
     * @brief Метод для парсинга текста по регулярным выражениям
     *
     * @param $text - текст
     * @return массив - распарсенный текст
     */

    public function parse_text($txt)
    {
        return $this;
    }

    /**
     * @brief Прверка имеет ли слово флаги
     *
     * @return true - если есть флаги и false инфче
     */

    public function has_flag()
    {
        foreach ($this as $key => $val) {
            if (!in_array($key, array('index', 'text', 'items', 'array_reg')) && $val)
                return true;
        }
        return false;
    }

    /**
     * @brief Получает флаги слова в виде массива
     *
     * @return получаем массив флагов
     */

    public function get_flags_array()
    {
        $result = array();
        foreach ($this as $key => $val) {
            if (!in_array($key, array('index', 'text', 'items', 'array_reg')) && $val)
                $result[$key] = $val;
        }
        return $result;
    }
}