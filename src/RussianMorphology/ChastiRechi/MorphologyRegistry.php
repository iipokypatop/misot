<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:51
 */

namespace Aot\RussianMorphology\ChastiRechi;


class MorphologyRegistry
{
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

    const CHISLO = 3000;
    const CHISLO_EDINSTVENNOE = 3001;
    const CHISLO_MNOZHESTVENNOE = 3002;

    const PEREHODNOST = 4000;
    const PEREHODNOST_PEREHODNII = 4001;
    const PEREHODNOST_NEPEREHODNII = 4002;

    public static function getNames()
    {
        return [
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

            static::CHISLO => 'число',
            static::CHISLO_EDINSTVENNOE => 'единственное число',
            static::CHISLO_MNOZHESTVENNOE => 'множественное число',

            static::PEREHODNOST => 'переходность',
            static::PEREHODNOST_PEREHODNII => 'переходный',
            static::PEREHODNOST_NEPEREHODNII => 'непереходный',
        ];
    }

    public static function getLvl1()
    {
        return array_keys(
            static::getClasses()
        );
    }

    public static function getLvl2()
    {
        $priznaki = [];
        foreach (static::getClasses() as $priznak => $variants) {
            $priznaki = array_merge(
                $priznaki,
                array_keys($variants)
            );
        }
        return $priznaki;
    }

    public static function getBaseClasses()
    {
        return [
            static::PADEJ => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Base::class,

            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Base::class,
            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base::class,
            ]
        ];
    }

    public static function getClassByChastRechiAndPriznak($priznak_input, $chast_rechi)
    {
        foreach (static::getClasses() as $priznak_group => $priznak) {
            foreach ($priznak as $classes) {
                if ($priznak_input === $priznak) {
                    if (!empty($classes[$chast_rechi])) {
                        return $classes[$chast_rechi];
                    }
                }
            }
        }

        return null;
    }

    public static function getClasses()
    {
        return [
            static::PADEJ => [
                static::PADEJ_IMENITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class,
                ],
                static::PADEJ_RODITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Roditelnij::class,
                ],
                static::PADEJ_DATELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Datelnij::class,
                ],
                static::PADEJ_VINITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Vinitelnij::class,
                ],
                static::PADEJ_TVORITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Tvoritelnij::class,
                ],
                static::PADEJ_PREDLOZSHNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Predlozshnij::class,
                ],
            ],
            static::ROD => [
                static::ROD_ZHENSKII => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskii::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Zhenskij::class,
                ],
                static::ROD_SREDNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Srednij::class,
                ],
                static::ROD_MUZHSKOI => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class,
                ],
            ],
            static::CHISLO => [
                static::CHISLO_EDINSTVENNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class,
                ],
                static::CHISLO_MNOZHESTVENNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::class,
                ]
            ],
            static::PEREHODNOST => [
                static::PEREHODNOST_PEREHODNII => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class,
                ],
                static::PEREHODNOST_NEPEREHODNII => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj::class,
                ]
            ]
        ];
    }
}




































