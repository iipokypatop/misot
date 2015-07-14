<?php
namespace Aot\RussianMorphology\ChastiRechi\Prichastie;
use Aot\RussianMorphology\Slovo;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Prichastie
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Forma\Base $litso
 * @property Morphology\Padeszh\Base $naklonenie
 * @property Morphology\Perehodnost\Base $perehodnost
 * @property Morphology\Rod\Base $rod
 * @property Morphology\Vid\Base $vid
 * @property Morphology\Vozvratnost\Base $vozvratnost
 * @property Morphology\Vremya\Base $vremya
 * @property Morphology\Razryad\Base $razryad
 */

class Base extends Slovo
{
    /**
     * @return array
     */
    public function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'forma' => Morphology\Forma\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class,
            'perehodnost' => Morphology\Perehodnost\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'vid' => Morphology\Vid\Base::class,
            'vozvratnost' => Morphology\Vozvratnost\Base::class,
            'vremya' => Morphology\Vremya\Base::class,
            'razryad' => Morphology\Razryad\Base::class,
        ];
    }
    /**
     * Prichastie constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Forma\Base $forma
     * @param Morphology\Padeszh\Base $padeszh
     * @param Morphology\Perehodnost\Base $perehodnost
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Vozvratnost\Base $vozvratnost
     * @param Morphology\Vremya\Base $vremya
     * @param Morphology\Razryad\Base $razryad
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Forma\Base $forma,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Perehodnost\Base $perehodnost,
        Morphology\Rod\Base $rod,
        Morphology\Vid\Base $vid,
        Morphology\Vozvratnost\Base $vozvratnost,
        Morphology\Vremya\Base $vremya,
        Morphology\Razryad\Base $razryad
    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->forma = $forma;
        $ob->padeszh = $padeszh;
        $ob->rod = $rod;
        $ob->perehodnost = $perehodnost;
        $ob->vid = $vid;
        $ob->vozvratnost = $vozvratnost;
        $ob->vremya = $vremya;
        $ob->razryad = $razryad;

        return $ob;
    }
}