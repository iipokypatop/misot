<?php

namespace Aot\MivarTextSemantic;

/**
 * class MorphAttribute
 *
 * @brief Класс для описания морфологического атрибута словоформы
 *
 */

class MorphAttribute
{

    public $id_morph_attr = null;
    /**< id морфологического атрибута */
    public $name = null;
    /**< имя морфологического атрибута */
    public $number_morph_attr = null;
    /**< номер по порядку морфологического атрибута */
    public $id_value_attr = array();
    /**< массив id значений морфологического атрибута */
    public $short_value = array();
    /**< массив кратких названий значений морфологического атрибута */
    public $value = array();
    /**< массив названий значений морфологического атрибута */

    /**
     * @brief Конструктор класса
     * @param $id_morph_attr id морфологического атрибута
     * @param $name имя морфологического атрибута
     * @param $number_morph_attr номер по порядку морфологического атрибута
     * @param $id_value_attr массив id значений морфологического атрибута: array(value=>value)
     * @param $short_value массив кратких названий значений морфологического атрибута array(value=>value)
     * @param $value массив названий значений морфологического атрибута array(value=>value)
     */

    public function __construct(
        $id_morph_attr = null,
        $name = null,
        $number_morph_attr = null,
        $id_value_attr = array(),
        $short_value = array(),
        $value = array()
    ) {
        $this->id_morph_attr = $id_morph_attr;
        $this->name = $name;
        $this->number_morph_attr = $number_morph_attr;
        $this->id_value_attr = $id_value_attr;
        $this->short_value = $short_value;
        $this->value = $value;
    }

    /**
     * @return id|null
     */
    public function getIdMorphAttr()
    {
        return $this->id_morph_attr;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNumberMorphAttr()
    {
        return $this->number_morph_attr;
    }

    /**
     * @return mixed
     */
    public function getIdValueAttr()
    {
        return $this->id_value_attr;
    }

    /**
     * @return mixed
     */
    public function getShortValue()
    {
        return $this->short_value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


}