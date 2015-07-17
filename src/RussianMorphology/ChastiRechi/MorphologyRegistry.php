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
    const PADESZH = 1000;
    const PADESZH_IMENITELNIJ = 1001;
    const PADESZH_RODITELNIJ = 1002;
    const PADESZH_DATELNIJ = 1003;
    const PADESZH_VINITELNIJ = 1004;
    const PADESZH_TVORITELNIJ = 1005;
    const PADESZH_PREDLOZSHNIJ = 1006;

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
    const RAZRYAD_PRITIAZHATELNOE = 9003;
    const RAZRYAD_VOZVRATNOE = 9004;
    const RAZRYAD_LICHNOE = 9005;

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

    const NAKLONENIE = 16000;
    const NAKLONENIE_IZYAVITELNOE = 16001;
    const NAKLONENIE_POVELITELNOE = 16002;
    const NAKLONENIE_YSLOVNOE = 16003;

    const VREMYA = 17000;
    const VREMYA_BUDUSCHEE = 17001;
    const VREMYA_NASTOYASCHEE = 17002;
    const VREMYA_PROSHEDSHEE = 17003;

    const LITSO = 18000;
    const LITSO_PERVOE = 18001;
    const LITSO_TRETIE = 18002;
    const LITSO_VTOROE = 18003;

    const ODUSHEVLYONNOST = 19000;
    const ODUSHEVLYONNOST_ODUSHEVLYONNOE = 19001;
    const ODUSHEVLYONNOST_NEODUSHEVLYONNOE = 19002;

    const NARITCATELNOST = 20000;
    const NARITCATELNOST_IMIA_NARITCATELNOE = 20001;
    const NARITCATELNOST_IMIA_SOBSTVENNOE = 20002;

    const PODVID = 21000;
    const PODVID_PROSTOY = 21001;
    const PODVID_SOSTAVNOY = 21002;

    const TIP = 22000;
    const TIP_CELIY = 22001;
    const TIP_DROBNIY = 22002;
    const TIP_SOBIRATELNIY = 22003;

    const VID_CHISLITELNOGO = 23000;
    const VID_CHISLITELNOGO_KOLICHESTVENNIY = 23001;
    const VID_CHISLITELNOGO_PORYADKOVIY = 23002;

    public static function getNames()
    {
        return [
            static::PADESZH => 'падеж',
            static::PADESZH_IMENITELNIJ => 'именительный падеж',
            static::PADESZH_RODITELNIJ => 'родительный падеж',
            static::PADESZH_DATELNIJ => 'дательный падеж',
            static::PADESZH_VINITELNIJ => 'винительный падеж',
            static::PADESZH_TVORITELNIJ => 'творительный падеж',
            static::PADESZH_PREDLOZSHNIJ => 'предложный падеж',

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

            static::ODUSHEVLENNOST => 'одушевленность',
            static::ODUSHEVLENNOST_ODUSHEVLENNOE => 'одушевленноеы',
            static::ODUSHEVLENNOST_NEODUSHEVLENNOE => 'неодушевленное',

            static::RAZRYAD => 'разряд',
            static::RAZRYAD_KACHESTVENNOE => 'качественное',
            static::RAZRYAD_OTNOSITELNOE => 'относительное',
            static::RAZRYAD_PRITIAZHATELNOE => 'притяжательное',
            static::RAZRYAD_VOZVRATNOE => 'возвратное',
            static::RAZRYAD_LICHNOE => 'личное',

            static::FORMA => 'форма',
            static::FORMA_POLNAYA => 'полная',
            static::FORMA_KRATKAYA => 'краткая',

            static::STEPEN_SRAVNENIYA => 'степень сравнения',
            static::STEPEN_SRAVNENIYA_POLOZHITELNAYA => 'положительная',
            static::STEPEN_SRAVNENIYA_SRAVNITELNAYA => 'сравнительная',
            static::STEPEN_SRAVNENIYA_PREVOSHODNAYA => 'превосходная',

            static::VID => 'вид',
            static::VID_SOVERSHENNYJ => 'совершенный',
            static::VID_NESOVERSHENNYJ => 'несовершенный',

            static::VOZVRATNOST => 'возвратность',
            static::VOZVRATNOST_VOZVRATNYJ => 'возвратный',
            static::VOZVRATNOST_NEVOZVRATNYJ => 'невозвратный',

            static::ZALOG => 'залог',
            static::ZALOG_DEJSTVITELNYJ => 'действительный',
            static::ZALOG_STRADATELNYJ => 'страдательный',

            static::SPRYAZHENIE => 'спряжение',
            static::SPRYAZHENIE_PERVOE => 'первое',
            static::SPRYAZHENIE_VTOROE => 'второе',

            static::NAKLONENIE => 'наклонение',
            static::NAKLONENIE_IZYAVITELNOE => 'изъявительное',
            static::NAKLONENIE_POVELITELNOE => 'повелительное',
            static::NAKLONENIE_YSLOVNOE => 'условное',

            static::VREMYA => 'время',
            static::VREMYA_BUDUSCHEE => 'будущее',
            static::VREMYA_NASTOYASCHEE => 'настоящее',
            static::VREMYA_PROSHEDSHEE => 'прошедшее',

            static::LITSO => 'лицо',
            static::LITSO_PERVOE => 'первое',
            static::LITSO_VTOROE => 'второе',
            static::LITSO_TRETIE => 'третье',

            static::ODUSHEVLYONNOST => 'одушевленность',
            static::ODUSHEVLYONNOST_ODUSHEVLYONNOE => 'одушевленное',
            static::ODUSHEVLYONNOST_NEODUSHEVLYONNOE => 'неодушевленное',

            static::NARITCATELNOST => 'нарицательность',
            static::NARITCATELNOST_IMIA_NARITCATELNOE => 'нарицательное',
            static::NARITCATELNOST_IMIA_SOBSTVENNOE => 'собственное',

            static::PODVID => 'подвид',
            static::PODVID_PROSTOY => 'простое',
            static::PODVID_SOSTAVNOY => 'составное',

            static::TIP => 'тип',
            static::TIP_CELIY => 'целое',
            static::TIP_DROBNIY => 'дробное',
            static::TIP_SOBIRATELNIY => 'собирательное',

            static::VID_CHISLITELNOGO => 'вид',
            static::VID_CHISLITELNOGO_KOLICHESTVENNIY => 'количественный',
            static::VID_CHISLITELNOGO_PORYADKOVIY => 'порядковый',
        ];
    }

    public static function getLvl1()
    {
        return array_keys(
            static::getClasses()
        );
    }

    public static function getClasses()
    {
        return [
            static::PADESZH => [
                static::PADESZH_IMENITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class,
                ],
                static::PADESZH_RODITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Roditelnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Roditelnij::class,
                ],
                static::PADESZH_DATELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Datelnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Datelnij::class,
                ],
                static::PADESZH_VINITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Vinitelnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class,
                ],
                static::PADESZH_TVORITELNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Tvoritelnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                ],
                static::PADESZH_PREDLOZSHNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Predlozshnij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Predlozshnij::class,
                ],
            ],
            static::ROD => [
                static::ROD_ZHENSKII => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskij::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Zhenskij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskij::class,
                ],
                static::ROD_SREDNIJ => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Srednij::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Srednij::class,
                ],
                static::ROD_MUZHSKOI => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Muzhskoi::class,
                ],
            ],
            static::CHISLO => [
                static::CHISLO_EDINSTVENNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Edinstvennoe::class,
                ],
                static::CHISLO_MNOZHESTVENNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::class,
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                ],
            ],
            static::PEREHODNOST => [
                static::PEREHODNOST_PEREHODNII => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Perehodnyj::class,
                ],
                static::PEREHODNOST_NEPEREHODNII => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class,
                ],
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
            static::FORMA => [
                static::FORMA_KRATKAYA => [
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Kratkaya::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Kratkaya::class
                ],
                static::FORMA_POLNAYA => [
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya::class
                ],
            ],
            static::ZALOG => [
                static::ZALOG_DEJSTVITELNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Dejstvitelnyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Dejstvitelnyj::class,
                ],
                static::ZALOG_STRADATELNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Stradatelnyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Stradatelnyj::class,
                ],
            ],
            static::RAZRYAD => [
                static::RAZRYAD_KACHESTVENNOE => [
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Kachestvennoe::class,
                ],
                static::RAZRYAD_OTNOSITELNOE => [
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Otnositelnoe::class,
                ],
                static::RAZRYAD_PRITIAZHATELNOE => [
                    ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Pritiazhatelnoe::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Pritiazhatelnoe::class,
                ],
                static::RAZRYAD_VOZVRATNOE => [
                    ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Vozvratnoe::class,
                ],
                static::RAZRYAD_LICHNOE => [
                    ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Lichnoe::class,
                ],
            ],
            static::NAKLONENIE => [
                static::NAKLONENIE_IZYAVITELNOE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe::class,
                ],
                static::NAKLONENIE_POVELITELNOE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Povelitelnoe::class,
                ],
                static::NAKLONENIE_YSLOVNOE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Yslovnoe::class,
                ],
            ],
            static::SPRYAZHENIE => [
                static::SPRYAZHENIE_PERVOE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe::class,
                ],
                static::SPRYAZHENIE_VTOROE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Vtoroe::class,
                ],
            ],
            static::LITSO => [
                static::LITSO_PERVOE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Pervoe::class,
                ],
                static::LITSO_VTOROE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Vtoroe::class,
                ],
                static::LITSO_TRETIE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::class,
                ],
            ],
            static::VID => [
                static::VID_SOVERSHENNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Sovershennyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj::class,
                ],
                static::VID_NESOVERSHENNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Nesovershennyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class,
                ],
            ],
            static::VOZVRATNOST => [
                static::VOZVRATNOST_VOZVRATNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Vozvratnyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Vozvratnyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Vozvratnyj::class,
                ],
                static::VOZVRATNOST_NEVOZVRATNYJ => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj::class,
                    ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Nevozvratnyj::class,
                ],
            ],
            static::STEPEN_SRAVNENIYA => [
                static::STEPEN_SRAVNENIYA_POLOZHITELNAYA => [
                    ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Polozhitelnaya::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Polozhitelnaya::class,
                ],
                static::STEPEN_SRAVNENIYA_PREVOSHODNAYA => [
                    ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Prevoshodnaya::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Prevoshodnaya::class,
                ],
                static::STEPEN_SRAVNENIYA_SRAVNITELNAYA => [
                    ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Sravnitelnaya::class,
                    ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Sravnitelnaya::class,
                ],
            ],
            static::ODUSHEVLYONNOST => [
                static::ODUSHEVLYONNOST_ODUSHEVLYONNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Odushevlyonnoe::class,
                ],
                static::ODUSHEVLYONNOST_NEODUSHEVLYONNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::class,
                ],
            ],
            static::VREMYA => [
                static::VREMYA_PROSHEDSHEE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Proshedshee::class,
                ],
                static::VREMYA_NASTOYASCHEE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Nastoyaschee::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee::class,
                ],
                static::VREMYA_BUDUSCHEE => [
                    ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Buduschee::class,
                    ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Buduschee::class,
                ],
            ],
            static::NARITCATELNOST => [
                static::NARITCATELNOST_IMIA_NARITCATELNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::class,
                ],
                static::NARITCATELNOST_IMIA_SOBSTVENNOE => [
                    ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaSobstvennoe::class,
                ],
            ],
            static::PODVID => [
                static::PODVID_PROSTOY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Prostoy::class
                ],
                static::PODVID_SOSTAVNOY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Sostavnoy::class
                ],
            ],
            static::TIP => [
                static::TIP_CELIY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Celiy::class
                ],
                static::TIP_DROBNIY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Drobniy::class
                ],
                static::TIP_SOBIRATELNIY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Sobiratelniy::class
                ],
            ],
            static::VID_CHISLITELNOGO => [
                static::VID_CHISLITELNOGO_KOLICHESTVENNIY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Kolichestvenniy::class
                ],
                static::VID_CHISLITELNOGO_PORYADKOVIY => [
                    ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Poryadkoviy::class
                ]
            ],
        ];
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

    public static function getChastRechiPriznaki()
    {
        $tmp = [];
        foreach (static::getClasses() as $priznak => $variants) {
            foreach ($variants as $priznak_id => $classes) {
                foreach ($classes as $chast_rechi_id => $class_name) {
                    $tmp[$chast_rechi_id][$priznak][] = $priznak_id;
                }
            }
        }

        return $tmp;
    }

    public static function getBaseClasses()
    {
        return [
            static::PADESZH => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Base::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Base::class,
            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Base::class,
            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Base::class,
            ],
            static::SKLONENIE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Base::class
            ],
            static::NEIZMENYAJMOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Base::class
            ],
            static::FORMA => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Base::class
            ],
            static::ZALOG => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Base::class,
            ],
            static::RAZRYAD => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Base::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Base::class,
            ],
            static::NAKLONENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Base::class,
            ],
            static::SPRYAZHENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Base::class,
            ],
            static::LITSO => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Base::class,
            ],
            static::VID => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Base::class,
            ],
            static::VOZVRATNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Base::class,
            ],
            static::STEPEN_SRAVNENIYA => [
                ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Base::class,
            ],
            static::ODUSHEVLYONNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Base::class,
            ],
            static::VREMYA => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Base::class,
            ],
            static::NARITCATELNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Base::class,
            ],
            static::PODVID => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Base::class,
            ],
            static::TIP => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Base::class,
            ],
            static::VID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Base::class,
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

    public static function getNullClasses()
    {
        return [
            static::PADESZH => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class,
            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class,

            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Null::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Null::class,

            ],
            static::SKLONENIE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::class
            ],
            static::NEIZMENYAJMOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\Null::class
            ],
            static::FORMA => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Null::class
            ],
            static::ZALOG => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Null::class,
            ],
            static::RAZRYAD => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class,
            ],
            static::NAKLONENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Null::class,
            ],
            static::SPRYAZHENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Null::class,
            ],
            static::LITSO => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Null::class,
            ],
            static::VID => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Null::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Null::class,
            ],
            static::VOZVRATNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Null::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class,
            ],
            static::STEPEN_SRAVNENIYA => [
                ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::class,
            ],
            static::ODUSHEVLYONNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Null::class,
            ],
            static::VREMYA => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Null::class,
            ],
            static::NARITCATELNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Null::class,
            ],
            static::PODVID => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class,
            ],
            static::TIP => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class,
            ],
            static::VID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Null::class,
            ],
        ];
    }
}
