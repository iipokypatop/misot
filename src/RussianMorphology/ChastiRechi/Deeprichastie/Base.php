<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 0:19
 */

namespace Aot\RussianMorphology\ChastiRechi\Deeprichastie;


use Aot\RussianMorphology\Slovo;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Deeprichastie
 * @property Morphology\Vid\Base $vid
 * @property Morphology\Perehodnost\Base $perehodnost
 * @property Morphology\Vozvratnost\Base $vozvratnost
 */
class Base extends Slovo
{
    public static function getMorphology()
    {
        return [
            'vid' => Morphology\Vid\Base::class,
            'perehodnost' => Morphology\Perehodnost\Base::class,
            'vozvratnost' => Morphology\Vozvratnost\Base::class
        ];
    }

    /**
     * Deeprichastie constructor.
     * @param $text
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Perehodnost\Base $perehodnost
     * @param Morphology\Vozvratnost\Base $vozvratnost
     * @return static
     */
    public static function create(
        $text,
        Morphology\Vid\Base $vid,
        Morphology\Perehodnost\Base $perehodnost,
        Morphology\Vozvratnost\Base $vozvratnost
    )
    {

        $ob = new static($text);

        $ob->vid = $vid;
        $ob->perehodnost = $perehodnost;
        $ob->vozvratnost = $vozvratnost;

        return $ob;
    }
}