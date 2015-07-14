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
    const NARECHIE = 13;
    const PRICHASTIE = 14;
    const DEEPRICHASTIE = 15;
    const CHISLITELNOE = 16;
    const MESTOIMENIE = 17;

    const PREDLOG = 19;


    public static function getNames()
    {
        return [
            static::SUSCHESTVITELNOE => 'существительное',
            static::PRILAGATELNOE => 'прилагательное',
            static::GLAGOL => 'глагол',
            static::PRICHASTIE => 'причастие',
            static::NARECHIE => 'наречие',
            static::PRICHASTIE => 'причастие',
            static::DEEPRICHASTIE => 'деепричастие',
            static::CHISLITELNOE => 'числительное',
            static::MESTOIMENIE => 'местоимение',

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