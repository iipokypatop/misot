<?php

namespace RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe;

use RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */
class Suschestvitelnoe extends Slovo
{
    /**
     * @var Morphology\Chislo\Base
     */
    protected $chislo;

    /** @var  Morphology\Naritcatelnost\Base */
    protected $naritcatelnost;

    /** @var   Morphology\Odushevlyonnost\Base */
    protected $odushevlyonnost;

    /**
     * @var Morphology\Padeszh\Base
     */
    protected $padeszh;

    /** @var  Morphology\Rod\Base */
    protected $rod;

    /** @var Morphology\Sklonenie\Base */
    protected $sklonenie;

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
    public function create(
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