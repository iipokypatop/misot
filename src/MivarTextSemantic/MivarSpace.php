<?php

namespace Aot\MivarTextSemantic;

/**
 * class MivarSpace
 *
 * @brief Класс для описания миварного пространства, состоящего из набора точек
 *
 */
class MivarSpace
{

    private $space = array();
    /**< массив точек пространства: array(ключ=>точка) */
    private $current_index = -1;
    /**< текущий индекс массива точек */
    private $max_index = -1;
    /**< максимальный индекс массива точек */

    /**
     * @brief Конструктор класса
     */

    public function __construct()
    {
        $this->space = array();
        $this->current_index = $this->max_index = -1;
    }

    /**
     * @brief Клонирование объекта
     */

    function __clone()
    {
        foreach ($this->space as &$point) {
            $point = clone $point;
        }
    }

    /**
     * @brief Добавление новой точки в пространство
     *
     * @param $point - точка - объект класса Point или любого, отнаследованного от него
     * @return ссылка на объект
     */

    public function add_point($point = null)
    {
        if ($point instanceof Point) {
            $this->space[++$this->max_index] = $point;
            $point->key_point = $this->current_index = $this->max_index;
        }
        return $this;
    }

    /**
     * @brief Получение текущей точки
     *
     * @return точка пространства или false в случае, если точки нет в массиве.
     */

    public function get_current_point()
    {
        return (isset($this->space[$this->current_index])) ? $this->space[$this->current_index] : false;
    }

    /**
     * @brief Получение точки по ключу
     *
     * @param $index - индекс точки массива
     * @return точка пространства или false в случае, если точки нет в массиве.
     */

    public function get_point($index)
    {
        return (isset($this->space[$index])) ? $this->space[$index] : false;
    }

    /**
     * @brief Установка текущего индекса массива точек
     *
     * @param $index - индекс точки массива
     * @return сылка на объект
     */

    public function set_current_index($index)
    {
        $this->current_index = ($index) ? $index : -1;
        return $this;
    }

    /**
     * @brief Получение множества точек пространства
     *
     * @return массив объектов точек пространства
     */

    public function get_space()
    {
        return $this->space;
    }

    /**
     * @brief Сброс текщего индекса
     *
     * @return сылка на объект
     */

    public function reset_current_index()
    {
        $this->current_index = -1;
        return $this;
    }

    /**
     * @brief Объединение пространств
     *
     * @param $mivar_space - объединяемое пространство
     * @return сылка на объект
     */

    public function add_to_space($mivar_space)
    {
        if ($mivar_space instanceof MivarSpace) {
            $this->space = array_merge($this->space, $mivar_space->get_space());
        }
        return $this;
    }

}