<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 07.07.2015
 * Time: 11:58
 */

namespace Aot\RussianMorphology;


class Map extends Registry
{


    public static function getEqClasses()
    {
        return [
            static::PADEJ => [
                static::PADEJ_IMENITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class,

                ],
                static::PADEJ_RODITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Padeszh\Roditelnij::class,
                ],
                static::PADEJ_DATELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Padeszh\Datelnij::class,
                ],
                static::PADEJ_VINITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Padeszh\Vinitelnij::class,
                ],
                static::PADEJ_TVORITELNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Padeszh\Tvoritelnij::class,
                ],
                static::PADEJ_PREDLOZSHNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Padeszh\Predlozshnij::class,
                ],
            ],
            static::ROD => [
                static::ROD_ZHENSKII => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskii::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Rod\Zhenskij::class,
                ],
                static::ROD_MUZHSKOI => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Rod\Muzhskoi::class,
                ],
                static::ROD_SREDNIJ => [
                    static::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class,
                    static::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class,
                    static::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij::class,
                    static::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Rod\Srednij::class,
                ],
            ],
            static::CHISLO => [
                static::CHISLO_EDINSTVENNOE => [
                    \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class,
                    \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class,
                    \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Chislo\Edinstvennoe::class,
                ],
                static::CHISLO_MNOZHESTVENNOE => [
                    \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    \Aot\RussianMorphology\ChastiRechi\prichastie\Morphology\Chislo\Mnozhestvennoe::class,
                ]
            ]
        ];
    }
}

