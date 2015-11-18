<?php

namespace Aot\MivarTextSemantic;


/**
 * class PointWdw
 *
 * @brief Класс для описания точки для пространства wdw
 * @author Елисеев Д.В.
 *
 */
class PointWdw extends Point
{

    public $kw = null;
    /**< номер слова в предложении */
    public $ks = null;
    /**< номер предложения в тексте */
    public $count_dw = null;
    /**< количество вариантов морфологических признаков из словаря */
    public $w = null;
    /**< слово: объект класса Word */
    public $dw = null;
    /**< словарная статья: объект класса Dw */


    /**
     * @brief Конструктор класса
     * @param $kw - номер слова в предложении
     * @param $ks - номер предложения в тексте
     * @param $count_dw - количество вариантов морфологических признаков из словаря
     * @param $w - слово: объект класса Word
     * @param $dw - словарная статья: объект класса Dw
     */

    public function __construct($kw = null,
                                $ks = null,
                                $count_dw = null,
                                $w = null,
                                $dw = null)
    {
        $this->key_point = null;
        $this->kw = (is_numeric($kw)) ? $kw : null;
        $this->ks = (is_numeric($ks)) ? $ks : null;
        $this->count_dw = (is_numeric($count_dw)) ? $count_dw : null;
        $this->w = ($w instanceof Word) ? $w : new Word();
        $this->dw = ($dw instanceof \DictionaryWord) ? $dw : new \DictionaryWord();
    }

    /**
     * @brief Создание объекта класса WdwOOz из текущего объекта
     * @param $o - тип отношения между словами
     * @param $oz - экземпляр отношения
     * @param $direction - направление связи
     * @param $id_sentence - id предложения
     * @return возвращает объект класса PointWdwOOz
     */

    public function create_point_wdwOOz($o = null, $oz = null, $direction = null, $id_sentence = null)
    {
        return new PointWdwOOz ($this->kw,
            $this->ks,
            $this->count_dw,
            $this->w,
            $this->dw,
            $o,
            $oz,
            $direction,
            $id_sentence);
    }
}

?>