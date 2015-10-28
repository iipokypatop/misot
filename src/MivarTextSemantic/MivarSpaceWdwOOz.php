<?php

namespace Aot\MivarTextSemantic;

/**
 * class MivarSpaceWdw
 * @author Eliseev
 * @brief Класс для описания миварного пространства, состоящего из набора точек wdw
 *
 */

class MivarSpaceWdwOOz extends MivarSpaceWdw
{
    protected $space_kw = array();
    /**< массив точек пространства, сгруппированных по kw (индекс по kw)*/

    /**
     * @brief Добавление новой точки в пространство с созданием объекта класса PointWdw
     *
     * @param $kw - номер слова в предложении
     * @param $ks - номер предложения в тексте
     * @param $count_dw - количество вариантов морфологических признаков из словаря
     * @param $w - слово: объект класса Word
     * @param $dw - словарная статья: объект класса Dw
     * @param $o - тип отношения между словами
     * @param $oz - экземпляр отношения
     * @param $direction - направление связи
     * @return ссылка на объект
     */

    public function add_point_wdwooz($kw, $ks, $count_dw, $w, $dw, $o, $oz, $direction)
    {
        return $this->add_point(new PointWdwOOz($kw, $ks, $count_dw, $w, $dw, $o, $oz, $direction));
    }

    /**
     * @brief Добавление новой точки в пространство (Перегрузка родительского метода)
     *
     * @param $point - точка - объект класса PointWdw или любого, отнаследованного от него
     * @return ссылка на объект
     */

    /*public function add_point($point = null) {
        if ($point instanceof PointWdwOOz) {
            parent::add_point($point);
        }
        return $this;
    }*/
}