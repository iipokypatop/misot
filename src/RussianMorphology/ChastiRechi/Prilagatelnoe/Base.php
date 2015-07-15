<?php

namespace Aot\RussianMorphology\ChastiRechi\Prilagatelnoe;

use Aot\RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */
/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Prilagatelnoe
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Forma\Base $forma
 * @property Morphology\Padeszh\Base $padeszh
 * @property Morphology\Razryad\Base $razryad
 * @property Morphology\Rod\Base $rod
 * @property Morphology\StepenSravneniya\Base $stepen_sravneniia
 */
class Base extends Slovo
{
    public function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'forma' => Morphology\Forma\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class,
            'razryad' => Morphology\Razryad\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'stepen_sravneniia' => Morphology\StepenSravneniya\Base::class,
        ];
    }

    /**
     * Prilagatelnoe constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Forma\Base $forma
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Razryad\Base $razryad
     * @param Morphology\Rod\Base $rod
     * @param Morphology\StepenSravneniya\Base $stepen_sravneniia
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Forma\Base $forma,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Razryad\Base $razryad,
        Morphology\Rod\Base $rod,
        Morphology\StepenSravneniya\Base $stepen_sravneniia

    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->forma = $forma;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->razryad = $razryad;
        $ob->stepen_sravneniia = $stepen_sravneniia;

        return $ob;
    }
}