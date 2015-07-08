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
 * @property Morphology\Razriad\Base $razriad
 * @property Morphology\Rod\Base $rod
 * @property Morphology\StepenSravneniia\Base $stepen_sravneniia
 */
class Base extends Slovo
{
    public function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'forma' => Morphology\Forma\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class,
            'razriad' => Morphology\Razriad\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'stepen_sravneniia' => Morphology\StepenSravneniia\Base::class,
        ];
    }

    /**
     * Prilagatelnoe constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Forma\Base $forma
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Razriad\Base $razriad
     * @param Morphology\Rod\Base $rod
     * @param Morphology\StepenSravneniia\Base $stepen_sravneniia
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Forma\Base $forma,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Razriad\Base $razriad,
        Morphology\Rod\Base $rod,
        Morphology\StepenSravneniia\Base $stepen_sravneniia

    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->forma = $forma;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->razriad = $razriad;
        $ob->stepen_sravneniia = $stepen_sravneniia;

        return $ob;
    }
}