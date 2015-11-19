<?php

namespace Aot\MivarTextSemantic\RegParser;


/**
 * class RegParser
 *
 * @brief Реализация парсера текста по регуляркам для выделения слов, предложений и т.д.
 *
 */


class RegParser
{

    public $parse_current_text;
    /**< текущий текст (массив) */

    public $current_list_words;
    /**< текущий список слов в тексте */

    /**< Массив регулярок для разбивки текста на части и функция обработки найденных шаблонов */

    protected $array_reg_part = array(
        "\n(\s)*\n" => "add_text_unit",
        "\n(\s)*([0-9]+\.)+" => "add_part_number"
    );

    /**< Массив регулярок для разбивки части на абзацы и функция обработки найденных шаблонов */

    protected $array_reg_paragraph = array("\.(\s)*\n" => "add_node_with_end");

    /**< Массив регулярок для разбивки абзаца на предложения и функция обработки найденных шаблонов */

    protected $array_reg_sentence = array(
        "[\.\?!](\s)+[А-Я]" => "add_sentence",
        "[\.\?!](\s)*$" => "add_sentence"
    );

    /**< Массив регулярок для разбивки предложения на слова и функция обработки найденных шаблонов */

    protected $array_reg_word = array(
        "[^А-Яа-яA-Za-z0-9ёЁ_\-]+" => "add_word",
        "(([0-9]{1,2}\.[0-9]{1,2}\.){0,1}[0-9]{4}(\s)*г\.)" => "add_date",
        "(([А-Я]\.(\s)*){0,1}[А-Я]\.(\s)*[А-Я][а-яё]*)" => "add_name",
        "((\s)*[\(\),:;«»\[\]](\s)*)" => "add_stop",
        "((\s)+[\–\-](\s)+)" => "add_stop",
        "[А-Яа-яA-Za-z0-9ёЁ_\-]+\.(\s)+" => "add_cut"
    );

    /**
     * @brief Метод для парсинга текста по регулярным выражениям
     *
     * @param $text - текст
     * @return массив - распарсенный текст
     */

    public function parse_text($txt)
    {
        $parse_text = array();
        $this->current_list_words = array();
        if ($txt) {

            // заменяем ё на е

            $txt = preg_replace("/ё/ui", "е", $txt);

            $parse_text['name'] = 'text';
            $parse_text['text'] = trim($txt);
            $parse_text['items'] = array();

            //Разбивка текста на части
            //$this->parse_text_unit($parse_text, array ("\r\n(\s)*\r\n" => "add_text_unit", "\r\n(\s)*([0-9]+\.)+" => "add_part_number"), 'part');
            $parse_text = $this->parse_text_unit($parse_text, $this->array_reg_part, 'part');
            if (isset($parse_text['items']) && is_array($parse_text['items'])) {
                foreach ($parse_text['items'] as &$part) {
                    $part = $this->parse_text_unit($part, $this->array_reg_paragraph, "paragraph");
                    if (isset($part['items']) && is_array($part['items'])) {
                        foreach ($part['items'] as &$paragraph) {
                            $paragraph = $this->parse_text_unit($paragraph, $this->array_reg_sentence, "sentence");
                            if (isset($paragraph['items']) && is_array($paragraph['items'])) {
                                foreach ($paragraph['items'] as &$sentence) {
                                    $sentence = $this->parse_text_unit($sentence, $this->array_reg_word, "word");
                                    if (isset($sentence['items']) && is_array($sentence['items'])) {
                                        foreach ($sentence['items'] as &$word) {
                                            if (!isset($word['data']) && !isset($word['name_fio']) &&
                                                !isset($word['stop']) && !isset($word['cut']) &&
                                                !isset($word['abbr'])
                                            ) {
                                                $word['text'] = mb_strtolower($word['text'], 'UTF-8');
                                                $text_word = $word['text'];
                                                $this->current_list_words[$text_word] = $text_word;
                                            }
                                            $word['id_sentence'] = $sentence['id_sentence'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->parse_current_text = $parse_text;
    }

    /**
     * @brief Метод разбивки текста на блоки
     *
     * @param &$text_unit - элемент текста
     * @param $array_reg_parts - регулярок для поиска
     * @param $name_unit - название блока текста
     * @return $text_unit массив - распарсенный текст
     */

    protected function parse_text_unit($text_unit, $array_reg_parts, $name_unit)
    {
        $text = $text_unit['text'];
        mb_regex_encoding("utf-8");
        mb_ereg_search_init($text);
        $poz = 0;
        reset($array_reg_parts);
        $lastReg = key($array_reg_parts);
        $lastMinPoz = null;
        $length = strlen($text);

        while (($length > $poz) && $minPoz = $this->minPozReg($array_reg_parts, $text, $poz)) {
            $minPoz["rezText"] = trim($minPoz["rezText"]);
            ParseTextFunctions::$array_reg_parts[$lastReg]($text_unit, $minPoz, $lastMinPoz, $name_unit);
            $poz = $minPoz["poz"];
            $lastReg = $minPoz["reg"];
            $lastMinPoz = $minPoz;
        }
        if ($length > $poz) {
            $minPoz["rezText"] = trim(mb_strcut($text, $poz));
        } else {
            $minPoz["rezText"] = "";
        }
        ParseTextFunctions::$array_reg_parts[$lastReg]($text_unit, $minPoz, $lastMinPoz, $name_unit);
        return $text_unit;
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
        foreach ($arrReg as $stRegKey => $stReg) {
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
}