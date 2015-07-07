<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 07.07.2015
 * Time: 11:58
 */

namespace Aot\RussianMorphology;


class Map
{
    const SUSCHESTVITELNOE = 10;
    const PRILAGATELNOE = 11;
    const GLAGOL = 12;

    const PADEJ = 1000;
    const PADEJ_IMENITELNIJ = 1001;
    const PADEJ_RODITELNIJ = 1002;
    const PADEJ_DATELNIJ = 1003;
    const PADEJ_VINITELNIJ = 1004;
    const PADEJ_TVORITELNIJ = 1005;
    const PADEJ_PREDLOZSHNIJ = 1006;

    const ROD = 2000;
    const ROD_MUZHSKOI = 2001;
    const ROD_ZHENSKII = 2002;
    const ROD_SREDNIJ = 2003;

    public static function getNames()
    {
        return [
            static::SUSCHESTVITELNOE => 'существительное',
            static::PRILAGATELNOE => 'прилагательное',
            static::GLAGOL => 'глагол',

            static::PADEJ => 'падеж',
            static::PADEJ_IMENITELNIJ => 'именительный падеж',
            static::PADEJ_RODITELNIJ => 'родительный падеж',
            static::PADEJ_DATELNIJ => 'дательный падеж',
            static::PADEJ_VINITELNIJ => 'винительный падеж',
            static::PADEJ_TVORITELNIJ => 'творительный падеж',
            static::PADEJ_PREDLOZSHNIJ => 'предложный падеж',

            static::ROD => 'род',
            static::ROD_MUZHSKOI => 'мужской род',
            static::ROD_ZHENSKII => 'женский род',
            static::ROD_SREDNIJ => 'средний род',
        ];
    }

    public static function getEqClasses()
    {
        return [
            static::PADEJ => [
                static::PADEJ_IMENITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class,
                ],
                static::PADEJ_RODITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij::class,
                ],
                static::PADEJ_DATELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij::class,
                ],
                static::PADEJ_VINITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::class,
                ],
                static::PADEJ_TVORITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::class,
                ],
                static::PADEJ_PREDLOZSHNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij::class,
                ],
            ],
            static::ROD => [
                static::ROD_ZHENSKII => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskii::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii::class,
                ],
                static::ROD_MUZHSKOI => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class,
                ],
                static::ROD_SREDNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij::class,
                ],
            ],
        ];
    }
}

