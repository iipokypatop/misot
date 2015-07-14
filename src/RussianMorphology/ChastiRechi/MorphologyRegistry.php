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

    const SKLONENIE = 4000;
    const SKLONENIE_PERVOE = 4001;
    const SKLONENIE_VTOROE = 4002;
    const SKLONENIE_TRETIE = 4003;

    const NEIZMENYAJMOST = 5000;
    const NEIZMENYAJMOST_IZMNYAJMIJ = 5001;
    const NEIZMENYAJMOST_NEIZMNYAJMIJ = 5002;

    const PEREHODNOST = 6000;
    const PEREHODNOST_PEREHODNII = 6001;
    const PEREHODNOST_NEPEREHODNII = 6002;

    const NARICATELNOST = 7000;
    const NARICATELNOST_NARICATELNOE = 7001;
    const NARICATELNOST_SOBSTVENNOE = 7002;

    const ODUSHEVLENNOST = 8000;
    const ODUSHEVLENNOST_ODUSHEVLENNOE = 8001;
    const ODUSHEVLENNOST_NEODUSHEVLENNOE = 8002;

    const RAZRYAD = 9000;
    const RAZRYAD_KACHESTVENNOE = 9001;
    const RAZRYAD_OTNOSITELNOE = 9002;

    const FORMA = 10000;
    const FORMA_POLNAYA = 10001;
    const FORMA_KRATKAYA = 10002;

    const STEPEN_SRAVNENIYA = 11000;
    const STEPEN_SRAVNENIYA_POLOZHITELNAYA = 11001;
    const STEPEN_SRAVNENIYA_SRAVNITELNAYA = 11002;
    const STEPEN_SRAVNENIYA_PREVOSHODNAYA = 11002;

    const VID = 12000;
    const VID_SOVERSHENNYJ = 12001;
    const VID_NESOVERSHENNYJ = 12002;

    const VOZVRATNOST = 13000;
    const VOZVRATNOST_VOZVRATNYJ = 13001;
    const VOZVRATNOST_NEVOZVRATNYJ = 13002;

    const ZALOG = 14000;
    const ZALOG_DEJSTVITELNYJ = 14001;
    const ZALOG_STRADATELNYJ = 14002;

    const SPRYAZHENIE = 15000;
    const SPRYAZHENIE_PERVOE = 15001;
    const SPRYAZHENIE_VTOROE = 15002;
    const SPRYAZHENIE_TRETIE = 15003;

    const NAKLONENIE = 16000;
    const NAKLONENIE_BASE = 16001;
    const NAKLONENIE_IZYAVITELNOE = 16002;
    const NAKLONENIE_POVELITELNOE = 16003;
    const NAKLONENIE_YSLOVNOE = 16004;

    const VREMYA = 17000;
    const VREMYA_BUDUSCHEE = 17001;
    const VREMYA_NASTOYASCHEE = 17002;
    const VREMYA_PROSHEDSHEE = 17003;

    const LITSO = 18000;
    const LITSO_PERVOE = 18001;
    const LITSO_TRETIE = 18002;
    const LITSO_VTOROE = 18003;

    CONST STEPENSRAVNENIIA = 19000;
    CONST STEPENSRAVNENIIA_POLOZHITELNAIA = 19001;
    CONST STEPENSRAVNENIIA_PREVOSHODNAIA = 19002;
    CONST STEPENSRAVNENIIA_SRAVNITELNAIA = 19003;

    public static function map()
    {
        return [
            ChastiRechiRegistry::SUSCHESTVITELNOE => [],
            ChastiRechiRegistry::PRILAGATELNOE => [],
            ChastiRechiRegistry::GLAGOL => [],
            ChastiRechiRegistry::NARECHIE => [],
            ChastiRechiRegistry::PRICHASTIE => [],
            ChastiRechiRegistry::DEEPRICHASTIE => [],
            ChastiRechiRegistry::CHISLITELNOE => [],
            ChastiRechiRegistry::MESTOIMENIE => [],

            ChastiRechiRegistry::PREDLOG => [],
        ];
    }

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

            static::SKLONENIE => 'склонение',
            static::SKLONENIE_PERVOE => 'первое склонение',
            static::SKLONENIE_VTOROE => 'второе склонение',
            static::SKLONENIE_TRETIE => 'третье склонение',

            static::NEIZMENYAJMOST => 'изменяемость',
            static::NEIZMENYAJMOST_IZMNYAJMIJ => 'неизменяемый',
            static::NEIZMENYAJMOST_NEIZMNYAJMIJ => 'изменяемый',


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

    public static function getVariantsLvl2()
    {
        $tmp = [];
        foreach (static::getClasses() as $priznak => $variants) {
            $tmp = array_merge(
                $tmp,
                array_values($variants)
            );
        }
        return $tmp;
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
            static::SKLONENIE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Base::class
            ],
            static::NEIZMENYAJMOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Base::class
            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base::class,
            ],
        ];
    }

    public static function getNullClasses()
    {
        return [
            static::PADEJ => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Null::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Null::class,

            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Null::class,
            ],
            static::SKLONENIE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::class
            ],
            static::NEIZMENYAJMOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Null::class
            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Null::class,
            ],
        ];
    }

    public static function getClassByChastRechiAndPriznak($chast_rechi_id, $priznak_id_input)
    {
        foreach (static::getClasses() as $priznak_group => $variants) {
            foreach ($variants as $priznak_id => $classes) {
                if ($priznak_id_input === $priznak_id) {
                    if (!empty($classes[$chast_rechi_id])) {
                        return $classes[$chast_rechi_id];
                    }
                }
            }
        }
        return null;
    }

    public static function getMorhologyGroups()
    {
        $rule_groups = [];

        foreach (static::getClasses() as $rule_group_id => $variants) {
            foreach ($variants as $rul) {

            }

        }


        return $rule_groups;
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
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskij::class,
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
            ],
            static::SKLONENIE => [
                static::SKLONENIE_PERVOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Pervoe::class
                ],
                static::SKLONENIE_VTOROE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Vtoroe::class
                ],
                static::SKLONENIE_TRETIE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Tretie::class
                ],
            ],
            static::NEIZMENYAJMOST => [
                static::NEIZMENYAJMOST_IZMNYAJMIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Izmnyajmij::class
                ],
                static::NEIZMENYAJMOST_NEIZMNYAJMIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Neizmnyajmij::class
                ],
            ],
        ];
    }
}






