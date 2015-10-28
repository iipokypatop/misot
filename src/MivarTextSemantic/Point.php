<?php

namespace Aot\MivarTextSemantic;

/**
 * class Point
 *
 * @brief Класс для описания точки для миварного пространства
 *
 */

class Point
{

    public $key_point = null;
    /**< ключ точки */

    /**
     * @brief Конструктор класса
     * @param $key_point - ключ точки
     */

    public function __construct($key_point = null)
    {
        $this->key_point = $key_point;
    }
}