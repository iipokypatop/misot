<?php

namespace Aot\MivarTextSemantic;

/**
 * class PointWdwOOz
 *
 * @brief Класс для описания точки для пространства wdwOOz
 * @author Елисеев Д.В.
 *
 */
class PointWdwOOz extends PointWdw
{

    public $o = null;
    /**< тип отношения между словами */
    public $oz = null;
    /**< экземпляр отношения */
    public $direction = null;
    /**< направление связи */
    public $id_sentence = null;
    /**< id предложения */


    /**
     * @brief Конструктор класса
     *
     * @param $kw - номер слова в предложении
     * @param $ks - номер предложения в тексте
     * @param $count_dw - количество вариантов морфологических признаков из словаря
     * @param $w - слово: объект класса Word
     * @param $dw - словарная статья: объект класса Dw
     * @param $o - тип отношения между словами
     * @param $oz - экземпляр отношения
     * @param $direction - направление связи
     * @param $id_sentence - id предложения
     */

    public function __construct(
        $kw = null,
        $ks = null,
        $count_dw = null,
        $w = null,
        $dw = null,
        $o = null,
        $oz = null,
        $direction = null,
        $id_sentence = null
    ) {
        parent::__construct($kw,
            $ks,
            $count_dw,
            $w,
            $dw);
        $this->o = (is_string($o)) ? $o : null;
        $this->oz = (is_string($oz)) ? $oz : null;
        $this->direction = (in_array($direction, array('x', 'y'))) ? $direction : null;
        $this->id_sentence = (is_string($id_sentence)) ? $id_sentence : null;
    }
}