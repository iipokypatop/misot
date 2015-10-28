<?php

namespace Aot\MivarTextSemantic;

/**
 * class Dw
 *
 * @brief Класс для описания словоформы с морфологическими атрибутами dw
 *
 */

class Dw
{

    public $id_word_form = null;
    /**< id словоформы */
    public $word_form = null;
    /**< словоформа */
    public $initial_form = null;
    /**< начальная форма словоформы */
    public $id_word_class = null;
    /**< id части речи */
    public $name_word_class = null;
    /**< название части речи */
    public $parameters = array();
    /**< морфологические атрибуты словоформы, массив array(id-морфологического втрибута => значение */

    /**
     * @brief Конструктор класса
     * @param $id_word_form - id словоформы
     * @param $word_form - словоформа
     * @param $initial_form - начальная форма словоформы
     * @param $id_word_class - id части речи
     * @param $name_word_class - название части речи
     * @param $parameters - морфологические атрибуты словоформы: массив array(id-морфологического втрибута => значение)
     */

    public function __construct($id_word_form = null,
                                $word_form = null,
                                $initial_form = null,
                                $id_word_class = null,
                                $name_word_class = null,
                                $parameters = array())
    {
        $this->id_word_form = $id_word_form;
        $this->word_form = $word_form;
        $this->initial_form = $initial_form;
        $this->id_word_class = $id_word_class;
        $this->name_word_class = $name_word_class;
        $this->parameters = $parameters;
    }

    /**
     * @brief Проверка наличия в параметрах слова нужного параметра и его значения. Можно задать только один из интересующих параметров, остальные паремтры должны быть пустыми
     * @param $id_word_class - часть речи
     * @param $id_morph_attribute - морфологический признак
     * @param $id_value - значение морфологического признака
     * @return возвращает булево значение true в случае присутствия в dw указанных признаков, иначе false
     */

    public function check_parameter($id_word_class = null, $id_morph_attribute = null, $id_value = null)
    {
        if ((!$id_word_class || $this->id_word_class == $id_word_class) &&
            (!$id_morph_attribute || isset($this->parameters[$id_morph_attribute])) &&
            (!$id_value || in_array($id_value, $this->parameters[$id_morph_attribute]->id_value_attr))
        ) {
            return true;
        }
        return false;
    }
}