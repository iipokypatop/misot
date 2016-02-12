<?php

namespace Aot\RussianMorphology\ChastiRechi\Infinitive;

use Aot\RussianMorphology\Slovo;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Infinitive
 * @property Morphology\Perehodnost\Base $perehodnost
 * @property Morphology\Vid\Base $vid
 * @property Morphology\Vozvratnost\Base $vozvratnost
 */
class Base extends Slovo
{
    public static function getMorphology()
    {
        return [
            'perehodnost' => Morphology\Perehodnost\Base::class,
            'vid' => Morphology\Vid\Base::class,
            'vozvratnost' => Morphology\Vozvratnost\Base::class,
        ];
    }

    /**
     * Infinitive constructor.
     * @param $text
     * @param Morphology\Perehodnost\Base $perehodnost
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Vozvratnost\Base $vozvratnost
     * @return static
     */
    public static function create(
        $text,
        Morphology\Perehodnost\Base $perehodnost,
        Morphology\Vid\Base $vid,
        Morphology\Vozvratnost\Base $vozvratnost
    )
    {

        $ob = new static($text);

        $ob->perehodnost = $perehodnost;
        $ob->vid = $vid;
        $ob->vozvratnost = $vozvratnost;

        return $ob;
    }
}