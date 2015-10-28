<?php

namespace Aot\MivarTextSemantic\RegParser;


class ParseTextFunctions
{

    // Добавляет элемент в массив c именем $name_node
    // $text_unit (объект класса domNode) - куда добавляем узел
    // $minPoz - массив разобранных параметров из строки
    // $lastMinPoz - предыдущий массив параметров.
    static public function add_text_unit(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {


        if ($minPoz["rezText"]) {
            $result['name'] = $name_unit;
            $result['text'] = $minPoz["rezText"];
            $text_unit['items'][] = $result; //  косяк!!!!!!!
            // return;
        }
    }

    //добавляет узел раздела с цифрами
    static public function add_part_number(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {

        if (!isset($lastMinPoz["regText"])) {
            $lastMinPoz["regText"] = "";
        }
        mb_ereg("((\d)+.)+", $lastMinPoz["regText"], $arr);
        $num = mb_strrchr(mb_substr($arr[0], 0, mb_strlen($arr[0]) - 1, 'UTF-8'), ".", true);
        $result['name'] = $name_unit;
        $result['text'] = $minPoz["rezText"];
        $result['number'] = $arr[0];
        $text_unit['items'][] = $result;
    }

    // Добавляем узел с регулярным выражением в конце
    static public function add_node_with_end(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {

        if ($minPoz["rezText"]) {
            $result['name'] = $name_unit;
            if (!isset($minPoz["regText"])) {
                $minPoz["regText"] = "";
            }
            $result['text'] = $minPoz["rezText"] . mb_substr($minPoz["regText"], 0, 1, 'UTF-8');
            $text_unit['items'][] = $result;
        }
    }

    // Добавляем узел предложения
    static public function add_sentence(&$text_unit, &$minPoz, $lastMinPoz, $name_unit)
    {
        if ($minPoz["rezText"]) {
            $result['name'] = $name_unit;
            if (!isset($minPoz["regText"])) {
                $minPoz["regText"] = "";
            }
            $result['endchar'] = mb_substr($minPoz["regText"], 0, 1, 'UTF-8');
            $result['id_sentence'] = uniqid('', true);
            $result['text'] = $minPoz["rezText"] . mb_substr($minPoz["regText"], 0, 1, 'UTF-8');
            if (isset($minPoz["reg"]) && $minPoz["reg"] == "[\.\?!](\s)+[А-Я]") {
                $minPoz["poz"] = $minPoz["poz"] - strlen(mb_substr($minPoz["regText"], -1, 1, 'UTF-8'));
            }
            $text_unit['items'][] = $result;
        }
    }

    //добавляет узел слова
    static public function add_word(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {

        if ($minPoz["rezText"]) {
            $result = array();
            $result['name'] = $name_unit;
            $result['text'] = $minPoz["rezText"];
            self::addSpecialAttributesToWord($result);
            $text_unit['items'][] = $result;
        }
    }

    // Добавляет узел с датой
    static public function add_date($text_unit, $minPoz, $lastMinPoz, $name_unit)
    {
        self::add_data($text_unit, $minPoz, $lastMinPoz, $name_unit, array('data' => true));
    }

    // Добавляет узел с имененм
    static public function add_name(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {
        self::add_data($text_unit, $minPoz, $lastMinPoz, $name_unit, array('name_fio' => true));
    }

    // Добавляем знак препинания
    static public function add_stop(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {
        self::add_data($text_unit, $minPoz, $lastMinPoz, $name_unit, array('stop' => true));
    }

    //добавляет узел слова, которое является сокращением
    static public function add_cut(&$text_unit, $minPoz, $lastMinPoz, $name_unit)
    {
        self::add_data($text_unit, $minPoz, $lastMinPoz, $name_unit, array('cut' => true));
    }

    /*
    * Базовый метод для добавления различных "узлов"
    */
    static private function add_data(&$text_unit, $minPoz, $lastMinPoz, $name_unit, $result)
    {
        if (!isset($lastMinPoz["regText"])) {
            $lastMinPoz["regText"] = "";
        }
        $lastMinPoz["regText"] = trim($lastMinPoz["regText"]);
        $result['name'] = $name_unit;
        $result['text'] = $lastMinPoz["regText"];
        $text_unit['items'][] = $result;
        self::add_word($text_unit, $minPoz, $lastMinPoz, $name_unit);
    }


    // Добавляет параметр в xml, что слово является аббревиатурой. Возможно дополниить проверками других параметров
    //$xml_node_part - xml узел, куда будет добавлен аттрибут
    //$text - анализируемый текст на совпадение с регулярным выражением
    static protected function addSpecialAttributesToWord(&$text_unit)
    {
        if (mb_ereg("[А-ЯЁ_\-]+", $text_unit['text'], $regText) && $regText[0] == $text_unit['text']) {
            if (($text_unit['text'] != 'Я') && ($text_unit['text'] != 'У') && ($text_unit['text'] != 'С') && ($text_unit['text'] != 'К') && ($text_unit['text'] != 'О') && ($text_unit['text'] != 'В')) {
                $text_unit['abbr'] = true;
            }
        }
    }

}