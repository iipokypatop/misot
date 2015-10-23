<?php

namespace Aot\MivarTextSemantic\dictionary;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\MorphAttribute;

class DMivarDictionary
{
    //Текущий словарь (массив слов)
    public $array_current_dictionary;

    // Массив ненайденных слов.
    public $array_missing_words;

    // Массив слов.
    public $array_words;

    // Массив начальных форм.
    public $array_initial_forms;

    protected static $dbconn;

    //Конструктор, выполняет создание объекта класса DOMDocument из массива уникальных слов
    //Вход: массив уникальных сл
    function __construct($words_array, $need_forms = false, $connection_string = \Aot\MivarTextSemantic\Constants::DB_CONNECTION)
    {
        self::$dbconn = pg_connect($connection_string);
        $this->array_words = $words_array;
        $this->array_current_dictionary = $this->get_words($words_array, $need_forms);
    }

    // Выполнеение запроса

    protected function query($sql)
    {
        return pg_query(self::$dbconn, $sql);
    }

    // Функция поиска слов в БД

    public function get_words($words_array, $need_forms = false, $use_predict = true, $use_object = true)
    {
        $result = array();
        $this->array_missing_words = array();
        if (is_array($words_array) && $words_array) {
            $find_word = array();

            $parts = array_chunk($words_array, 100);
            foreach ($parts as $part) {
                $str_array_words = "'" . implode("','", $part) . "'";
                if ($need_forms) {
                    $str_part_where = " WHERE		word_form.initial_form IN ($str_array_words)";
                } else {
                    $str_part_where = " WHERE		word_form.word_form IN ($str_array_words)";
                }
                $strQuery = "	SELECT
							word_form.word_form, 
							word_form.id_word_class, 
							word_form.priority, 
							word_form.id_word_form, 
							word_form.initial_form,
							word_class.name AS name_word_class,
							value_attribute.short_value AS short_value,
							value_attribute.value AS name_value_attr,
							word_class_attribute.number_attr,
							value_attribute.id_value_attr,
							word_form_parameter.id_form_param AS uuid_form_param,
							morph_attribute.id_morph_attr AS id_morph_attr,
							morph_attribute.name AS name_attribute
						FROM 
								public.word_form
						INNER JOIN 	public.word_class
						ON		(word_form.id_word_class = word_class.id_word_class)
						LEFT JOIN 	public.word_form_parameter 
						ON 		(word_form.id_word_form = word_form_parameter.id_word_form)
						LEFT JOIN 	public.value_attribute
						ON		(word_form_parameter.id_value_attr = value_attribute.id_value_attr)
						LEFT JOIN 	public.morph_attribute
						ON 		(value_attribute.id_morph_attr = morph_attribute.id_morph_attr)
						LEFT JOIN 	public.word_class_attribute
						ON		(morph_attribute.id_morph_attr = word_class_attribute.id_morph_attr
								AND word_class.id_word_class = word_class_attribute.id_word_class)" .
                    $str_part_where
                    . "ORDER BY
								word_form.word_form ASC,
								word_form.id_word_form ASC,
								word_class_attribute.number_attr ASC,
								value_attribute.id_value_attr ASC
				;";

                // dpm($strQuery);
                $res = $this->query($strQuery);
                $currentUuid = "";
                while ($line = pg_fetch_assoc($res)) {
                    if ($currentUuid != $line['id_word_form']) {
                        $currentUuid = $line['id_word_form'];
                        $word_form = $line['word_form'];
                        $find_word[$word_form] = $word_form;
                        $result[$word_form][$currentUuid]['id_word_form'] = $line['id_word_form'];
                        $result[$word_form][$currentUuid]['word_form'] = $word_form;
                        $result[$word_form][$currentUuid]['initial_form'] = $line['initial_form'];
                        $result[$word_form][$currentUuid]['id_word_class'] = $line['id_word_class'];
                        $result[$word_form][$currentUuid]['name_word_class'] = $line['name_word_class'];
                        $result[$word_form][$currentUuid]['priority'] = $line['priority'];
                        $result[$word_form]['id_word_classes'][$line['id_word_class']] = $line['id_word_class'];
                        $result[$word_form]['initial_forms'][$line['initial_form']] = $line['initial_form'];
                    }
                    if ($line['short_value']) {
                        $id_morph_attr = $line['id_morph_attr'];
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['id_morph_attr'] = $id_morph_attr;
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['name'] = $line['name_attribute'];
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['number_morph_attr'] = $line['number_attr'];
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['id_value_attr'][$line['id_value_attr']] = $line['id_value_attr'];
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['short_value'][$line['short_value']] = $line['short_value'];
                        $result[$word_form][$currentUuid]['parametrs'][$id_morph_attr]['value'][$line['name_value_attr']] = $line['name_value_attr'];
                    }
                }
            }


            // модуль предсказаний

            $this->array_missing_words = array_diff($words_array, $find_word);
            if ($use_predict && $this->array_missing_words) {
                $miss_words_predict = \Aot\MivarTextSemantic\dictionary\Helper::getWordFromAllDict($this->array_missing_words);
                if ($miss_words_predict) {
                    foreach ($miss_words_predict as $word => $dict_words) {
                        $result[$word] = $dict_words;
                        foreach ($dict_words as $dict_word)
                            $result[$word]['id_word_classes'][$dict_word['id_word_class']] = $dict_word['id_word_class'];
                        $result[$word]['initial_forms'][$dict_word['initial_form']] = $dict_word['initial_form'];
                    }
                }
            }
        }
        /*echo "<pre>";
        print_r($result);
        echo "</pre>";*/
        if ($use_object && $result) {
            foreach ($result as $kew_w => &$word) {
                if ($word) {
                    foreach ($word as $kew_dw => &$dw) {
                        if (isset($dw['parametrs'])) {
                            foreach ($dw['parametrs'] as $key_param => &$param) {
                                $param = new MorphAttribute($param['id_morph_attr'],
                                    $param['name'],
                                    isset($param['number_morph_attr']) ? $param['number_morph_attr'] : 0,
                                    $param['id_value_attr'],
                                    $param['short_value'],
                                    isset($param['value']) ? $param['value'] : array());
                            }
                            $dw = new Dw ($dw['id_word_form'],
                                $dw['word_form'],
                                $dw['initial_form'],
                                $dw['id_word_class'],
                                $dw['name_word_class'],
                                $dw['parametrs']);
                        } else if (isset($dw['id_word_form'])) {
                            $dw = new Dw ($dw['id_word_form'],
                                $dw['word_form'],
                                $dw['initial_form'],
                                $dw['id_word_class'],
                                $dw['name_word_class']);
                        }
                    }
                }
            }
        }
        return $result;
    }

    // проверка соответствия слова начальной форме

    static public function check_initial_form($dictionary_word, $id_word_class)
    {
        if ($dictionary_word['id_word_class'] == $id_word_class &&
            $dictionary_word['word_form'] == $dictionary_word['initial_form']
        ) {
            return true;
        }
        return false;
    }

    //сравнение совпадающих параметров слов для согласования

    static public function compare_parameters($dict_word1, $dict_word2, $parameters)
    {
        foreach ($parameters as $param) {
            if (isset($dict_word1['parametrs'][$param], $dict_word2['parametrs'][$param])) {
                foreach ($dict_word1['parametrs'][$param]['id_value_attr'] as $id_value) {
                    if (!in_array($id_value, $dict_word2['parametrs'][$param]['id_value_attr']))
                        return false;
                }
            }
        }
        return true;
    }

}
