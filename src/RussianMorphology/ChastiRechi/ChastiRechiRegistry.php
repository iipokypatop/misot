<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:16
 */

namespace Aot\RussianMorphology\ChastiRechi;


class ChastiRechiRegistry
{
    const SUSCHESTVITELNOE = 10;
    const PRILAGATELNOE = 11;
    const GLAGOL = 12;
    const PRICHASTIE = 13; // ??
    const NARECHIE = 14; // ??

    const PREDLOG = 19; // ??

    public static function getNames()
    {
        return [
            static::SUSCHESTVITELNOE => 'существительное',
            static::PRILAGATELNOE => 'прилагательное',
            static::GLAGOL => 'глагол',
            static::PRICHASTIE => 'причастие',
            static::NARECHIE => 'наречие',
            static::PREDLOG => 'предлог',
        ];
    }

    public static function getClasses()
    {
        return [
            static::SUSCHESTVITELNOE => Suschestvitelnoe\Base::class,
            static::PRILAGATELNOE =>  Prilagatelnoe\Base::class,
            static::GLAGOL =>  Glagol\Base::class,
            static::PRICHASTIE =>  Prichastie\Base::class,
            static::NARECHIE =>  Narechie\Base::class,
            static::PREDLOG => Predlog::class,
        ];
    }
}