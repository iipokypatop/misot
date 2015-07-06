<?php

namespace Aot\RussianMorphology\ChastiRechi\Prilagatelnoe;

use Aot\RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */
class Base extends Slovo
{
    /**@var Morphology\Chislo\Base*/
    public $chislo;

    /**@var Morphology\Forma\Base*/
    public $forma;

    /**@var Morphology\Padeszh\Base*/
    public $padeszh;

    /**@var Morphology\Razriad\Base*/
    public $razriad;

    /**@var Morphology\Rod\Base*/
    public $rod;

    /**@var Morphology\StepenSravneniia\Base*/
    public $stepen_sravneniia;

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