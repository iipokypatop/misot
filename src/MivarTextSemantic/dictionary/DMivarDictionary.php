<?php

namespace Aot\MivarTextSemantic\dictionary;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\MorphAttribute;

class DMivarDictionary
{

    public $array_missing_words;

    public $array_current_dictionary;


    public $array_words;

    public function __construct($words_array, $need_forms = false, $connection_string = null)
    {
        $this->array_words = $words_array;
        $this->array_current_dictionary = $this->get_words($words_array, $need_forms);

    }

    public function get_words($words_array, $need_forms = false, $use_predict = true, $use_object = true)
    {
        return $this::predict($words_array);
    }

    public static function predict(array $words_array)
    {
        $result = array();

        if (count($words_array) > 0) {

            $miss_words_predict = \Aot\MivarTextSemantic\dictionary\Helper::getWordFromAllDict($words_array);

            if ($miss_words_predict) {

                foreach ($miss_words_predict as $word => $dict_words) {
                    $result[$word] = $dict_words;
                    if (!empty($dict_words)) {
                        foreach ($dict_words as $dict_word)
                            $result[$word]['id_word_classes'][$dict_word['id_word_class']] = $dict_word['id_word_class'];
                        if (!empty($dict_word)) {
                            $result[$word]['initial_forms'][$dict_word['initial_form']] = $dict_word['initial_form'];
                        }
                    }
                }
            }
        }

        if ($result) {
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
                            $dw = new \DictionaryWord($dw['id_word_form'],
                                $dw['word_form'],
                                $dw['initial_form'],
                                $dw['id_word_class'],
                                $dw['name_word_class'],
                                $dw['parametrs']);
                        } else if (isset($dw['id_word_form'])) {
                            $dw = new \DictionaryWord($dw['id_word_form'],
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
}
