<?php

namespace RussianMorphology\ChastiRechi\Glagol;

use RussianMorphology\Slovo;
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

    /**@var Morphology\Litso\Base*/
    public $litso;

    /**@var Morphology\Naklonenie\Base*/
    public $naklonenie;

    /**@var Morphology\Perehodnost\Base*/
    public $perehodnost;

    /**@var Morphology\Rod\Base*/
    public $rod;

    /**@var Morphology\Spryazhenie\Base*/
    public $spryazhenie;

    /**@var Morphology\Vid\Base*/
    public $vid;

    /**
     * Prilagatelnoe constructor.
     * @param $text
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Litso\Base $litso
     * @param Morphology\Naklonenie\Base $naklonenie
     * @param Morphology\Perehodnost\Base $perehodnost
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Spryazhenie\Base $spryazhenie
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Vozvratnost\Base $vozvratnost
     * @param Morphology\Vremya\Base $vremya
     * @param Morphology\Zalog\Base $zalog
     * @return static
     */
    public static function create(
        $text,
        Morphology\Chislo\Base $chislo,
        Morphology\Litso\Base $litso,
        Morphology\Naklonenie\Base $naklonenie,
        Morphology\Perehodnost\Base $perehodnost,
        Morphology\Rod\Base $rod,
        Morphology\Spryazhenie\Base $spryazhenie,
        Morphology\Vid\Base $vid,
        Morphology\Vozvratnost\Base $vozvratnost,
        Morphology\Vremya\Base $vremya,
        Morphology\Zalog\Base $zalog
    )
    {
        $ob = new static($text);

        $ob->chislo = $chislo;
        $ob->$litso = $litso;
        $ob->naklonenie = $naklonenie;
        $ob->perehodnost = $perehodnost;
        $ob->rod = $rod;
        $ob->spryazhenie = $spryazhenie;
        $ob->vid = $vid;
        $ob->rod = $vozvratnost;
        $ob->spryazhenie = $vremya;
        $ob->vid = $zalog;

        return $ob;
    }
}