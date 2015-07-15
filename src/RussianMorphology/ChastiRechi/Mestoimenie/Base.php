<?php
namespace Aot\RussianMorphology\ChastiRechi\Mestoimenie;


use Aot\RussianMorphology\Slovo;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Mestoimenie
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Litso\Base $litso
 * @property Morphology\Padeszh\Base $padeszh
 * @property Morphology\Razryad\Base $razryad
 * @property Morphology\Rod\Base $rod
 */
class Base extends Slovo
{
    public function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'litso' => Morphology\Litso\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class,
            'razryad' => Morphology\Razryad\Base::class,
            'rod' => Morphology\Rod\Base::class
        ];
    }

    /**
     * Mestoimenie constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Litso\Base $litso
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Razryad\Base $razryad
     * @param Morphology\Rod\Base $rod
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Litso\Base $litso,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Razryad\Base $razryad,
        Morphology\Rod\Base $rod

    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->litso = $litso;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->razryad = $razryad;

        return $ob;
    }

}