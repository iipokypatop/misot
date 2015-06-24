<?php

namespace RussianMorphology\ChastiRechi\Suschestvitelnoe;

use RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */
class Base extends Slovo
{
    /**
     * @var Morphology\Chislo\Base
     */
    public $chislo;

    /** @var  Morphology\Naritcatelnost\Base */
    public $naritcatelnost;

    /** @var   Morphology\Odushevlyonnost\Base */
    public $odushevlyonnost;

    /**
     * @var Morphology\Padeszh\Base
     */
    public $padeszh;

    /** @var  Morphology\Rod\Base */
    public $rod;

    /** @var Morphology\Sklonenie\Base */
    public $sklonenie;


    /**
     * Suschestvitelnoe constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Naritcatelnost\Base $naritcatelnost
     * @param Morphology\Odushevlyonnost\Base $odushevlyonnost
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Sklonenie\Base $sklonenie
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Naritcatelnost\Base $naritcatelnost,
        Morphology\Odushevlyonnost\Base $odushevlyonnost,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Rod\Base $rod,
        Morphology\Sklonenie\Base $sklonenie
    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->naritcatelnost = $naritcatelnost;
        $ob->odushevlyonnost = $odushevlyonnost;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->sklonenie = $sklonenie;

        return $ob;
    }


}