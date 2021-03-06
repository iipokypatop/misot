<?php

namespace Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe;

use Aot\RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Naritcatelnost\Base $naritcatelnost
 * @property Morphology\Odushevlyonnost\Base $odushevlyonnost
 * @property Morphology\Padeszh\Base $padeszh
 * @property Morphology\Rod\Base $rod
 * @property Morphology\Sklonenie\Base $sklonenie
 * @property Morphology\Otglagolnost\Base $otglagolnost
 * @property Morphology\DopPriznaki\Base $dop_priznak
 */
class Base extends Slovo
{
    public static function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'naritcatelnost' => Morphology\Naritcatelnost\Base::class,
            'odushevlyonnost' => Morphology\Odushevlyonnost\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'sklonenie' => Morphology\Sklonenie\Base::class,
            'otglagolnost' => Morphology\Otglagolnost\Base::class,
            'dop_priznak' => Morphology\DopPriznaki\Base::class,
        ];
    }

    /**
     * Suschestvitelnoe constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Naritcatelnost\Base $naritcatelnost
     * @param Morphology\Odushevlyonnost\Base $odushevlyonnost
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Sklonenie\Base Glagol
     * @param Morphology\Otglagolnost\Base $otglagolnost
     * @param Morphology\DopPriznaki\Base $dop_priznak
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Naritcatelnost\Base $naritcatelnost,
        Morphology\Odushevlyonnost\Base $odushevlyonnost,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Rod\Base $rod,
        Morphology\Sklonenie\Base $sklonenie,
        Morphology\Otglagolnost\Base $otglagolnost,
        Morphology\DopPriznaki\Base $dop_priznak = null
    ) {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->naritcatelnost = $naritcatelnost;
        $ob->odushevlyonnost = $odushevlyonnost;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->sklonenie = $sklonenie;
        $ob->otglagolnost = $otglagolnost;
        $ob->dop_priznak = $dop_priznak !== null ? $dop_priznak : Morphology\DopPriznaki\ClassNull::create();

        return $ob;
    }
}