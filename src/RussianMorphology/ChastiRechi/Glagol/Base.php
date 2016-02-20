<?php

namespace Aot\RussianMorphology\ChastiRechi\Glagol;

use Aot\RussianMorphology\Slovo;


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:11
 */

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Glagol
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Litso\Base $litso
 * @property Morphology\Naklonenie\Base $naklonenie
 * @property Morphology\Perehodnost\Base $perehodnost
 * @property Morphology\Rod\Base $rod
 * @property Morphology\Spryazhenie\Base $spryazhenie
 * @property Morphology\Vid\Base $vid
 * @property Morphology\Vozvratnost\Base $vozvratnost
 * @property Morphology\Vremya\Base $vremya
 * @property Morphology\Zalog\Base $zalog
 */
class Base extends Slovo //\Aot\RussianMorphology\ChastiRechi\Infinitive\Base
{
    public static function getMorphology()
    {
        return [
            'chislo' => Morphology\Chislo\Base::class,
            'litso' => Morphology\Litso\Base::class,
            'naklonenie' => Morphology\Naklonenie\Base::class,
            'vid' => Morphology\Vid\Base::class,
            'perehodnost' => Morphology\Perehodnost\Base::class,
            'vozvratnost' => Morphology\Vozvratnost\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'spryazhenie' => Morphology\Spryazhenie\Base::class,
            'vremya' => Morphology\Vremya\Base::class,
            'zalog' => Morphology\Zalog\Base::class,
        ];
    }

    /**
     * Glagol constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Litso\Base $litso
     * @param Morphology\Naklonenie\Base $naklonenie
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Perehodnost\Base $perehodnost
     * @param Morphology\Vozvratnost\Base $vozvratnost
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Spryazhenie\Base $spryazhenie
     * @param Morphology\Vremya\Base $vremya
     * @param Morphology\Zalog\Base $zalog
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Litso\Base $litso,
        Morphology\Naklonenie\Base $naklonenie,
        Morphology\Vid\Base $vid,
        Morphology\Perehodnost\Base $perehodnost,
        Morphology\Vozvratnost\Base $vozvratnost,
        Morphology\Rod\Base $rod,
        Morphology\Spryazhenie\Base $spryazhenie,
        Morphology\Vremya\Base $vremya,
        Morphology\Zalog\Base $zalog
    )
    {

        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->litso = $litso;
        $ob->naklonenie = $naklonenie;
        $ob->perehodnost = $perehodnost;
        $ob->rod = $rod;
        $ob->spryazhenie = $spryazhenie;
        $ob->vid = $vid;
        $ob->vozvratnost = $vozvratnost;
        $ob->vremya = $vremya;
        $ob->zalog = $zalog;

        return $ob;
    }
}