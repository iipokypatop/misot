<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:51
 */

namespace Aot\RussianMorphology\ChastiRechi;

class MorphologyRegistry extends MorphologyRegistryParent
{

    const PADESZH_IMENITELNIJ = 1001;
    const PADESZH_RODITELNIJ = 1002;
    const PADESZH_DATELNIJ = 1003;
    const PADESZH_VINITELNIJ = 1004;
    const PADESZH_TVORITELNIJ = 1005;
    const PADESZH_PREDLOZSHNIJ = 1006;

    const ROD_MUZHSKOI = 2001;
    const ROD_ZHENSKII = 2002;
    const ROD_SREDNIJ = 2003;

    const CHISLO_EDINSTVENNOE = 3001;
    const CHISLO_MNOZHESTVENNOE = 3002;
    const CHISLO_NULL = 3003;

    const SKLONENIE_PERVOE = 4001;
    const SKLONENIE_VTOROE = 4002;
    const SKLONENIE_TRETIE = 4003;
    const SKLONENIE_NESKLONJAEMOE = 4004;

    const NEIZMENYAJMOST_IZMNYAJMIJ = 5001;
    const NEIZMENYAJMOST_NEIZMNYAJMIJ = 5002;

    const PEREHODNOST_PEREHODNII = 6001;
    const PEREHODNOST_NEPEREHODNII = 6002;

    const NARICATELNOST_NARICATELNOE = 7001;
    const NARICATELNOST_SOBSTVENNOE = 7002;


    const RAZRYAD_MESTOIMENIE_OTNOSITELNOE = 9002;
    const RAZRYAD_MESTOIMENIE_PRITYAZHATELNOE = 9003;
    const RAZRYAD_MESTOIMENIE_VOZVRATNOE = 9004;
    const RAZRYAD_MESTOIMENIE_LICHNOE = 9005;
    const RAZRYAD_MESTOIMENIE_NEOPREDELENNOE = 9006;
    const RAZRYAD_MESTOIMENIE_OPREDELITELNOE = 9007;
    const RAZRYAD_MESTOIMENIE_OTRICATELNOE = 9008;
    const RAZRYAD_MESTOIMENIE_UKAZATELNOE = 9009;
    const RAZRYAD_MESTOIMENIE_VOPROSITELNOE = 9010;

    const FORMA_POLNAYA = 10002;
    const FORMA_KRATKAYA = 10001;

    const STEPEN_SRAVNENIYA_POLOZHITELNAYA = 11001;
    const STEPEN_SRAVNENIYA_SRAVNITELNAYA = 11002;
    const STEPEN_SRAVNENIYA_PREVOSHODNAYA = 11003;

    const VID_SOVERSHENNYJ = 12001;
    const VID_NESOVERSHENNYJ = 12002;

    const VOZVRATNOST_VOZVRATNYJ = 13001;
    const VOZVRATNOST_NEVOZVRATNYJ = 13002;

    const ZALOG_DEJSTVITELNYJ = 14001;
    const ZALOG_STRADATELNYJ = 14002;

    const SPRYAZHENIE_PERVOE = 15001;
    const SPRYAZHENIE_VTOROE = 15002;

    const NAKLONENIE_IZYAVITELNOE = 16001;
    const NAKLONENIE_POVELITELNOE = 16002;
    const NAKLONENIE_YSLOVNOE = 16003;
    const NAKLONENIE_NULL = 16004;

    const VREMYA_BUDUSCHEE = 17001;
    const VREMYA_NASTOYASCHEE = 17002;
    const VREMYA_PROSHEDSHEE = 17003;
    const VREMYA_NULL = 17004;

    const LITSO_PERVOE = 18001;
    const LITSO_TRETIE = 18002;
    const LITSO_VTOROE = 18003;
    const LITSO_NULL = 18004;

    const ODUSHEVLYONNOST_ODUSHEVLYONNOE = 19001;
    const ODUSHEVLYONNOST_NEODUSHEVLYONNOE = 19002;

    const NARITCATELNOST_IMIA_NARITCATELNOE = 20001;
    const NARITCATELNOST_IMIA_SOBSTVENNOE = 20002;

    const PODVID_CHISLITELNOGO_PROSTOY = 21001;
    const PODVID_CHISLITELNOGO_SOSTAVNOY = 21002;
    const PODVID_CHISLITELNOGO_SLOZHNOE = 21003;

    const TIP_CHISLITELNOGO_CELIY = 22001;
    const TIP_CHISLITELNOGO_DROBNIY = 22002;
    const TIP_CHISLITELNOGO_SOBIRATELNIY = 22003;
    const TIP_CHISLITELNOGO_LICHNOE_ODUSHEVLENNOE = 22004;

    const VID_CHISLITELNOGO_KOLICHESTVENNIY = 23001;
    const VID_CHISLITELNOGO_PORYADKOVIY = 23002;

    const RAZRYAD_PRILAGATELNOE_KACHESTVENNOE = 24001;
    const RAZRYAD_PRILAGATELNOE_OTNOSITELNOE = 24002;
    const RAZRYAD_PRILAGATELNOE_PRITYAZHATELNOE = 24003;


    const OTGLAGOLNOST_SUSCHESTVITELNOE_NEOTGLAGOLNOE = 25001;
    const OTGLAGOLNOST_SUSCHESTVITELNOE_OTGLAGOLNOE = 25002;

    const PROIZVODNOST_PREDLOG_PROIZVODNIJ = 26001;
    const PROIZVODNOST_PREDLOG_NEPROIZVODNIJ = 26002;


    const RAZRYAD_NARECHIE_OPREDELITELNYJ_OBRAZA_DEJSTVIJA = 27001;
    const RAZRYAD_NARECHIE_OPREDELITELNYJ_MERY_I_STEPENI = 27002;


    const RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_MESTA = 27011;
    const RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_VREMENI = 27012;
    const RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_PRICHINY = 27013;
    const RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_CELI = 27014;


    const RAZRJAD_PREDLOG_PROSTRANSTVENNYJ = 28001;
    CONST RAZRJAD_PREDLOG_VREMENNOJ = 28002;
    CONST RAZRJAD_PREDLOG_PRICHINNYJ = 28003;
    CONST RAZRJAD_PREDLOG_CELEVOJ = 28004;
    CONST RAZRJAD_PREDLOG_OBRAZA_DEJSTVIJA = 28005;
    CONST RAZRJAD_PREDLOG_DOPOLNITELNYJ = 28006;

    CONST RAZRJAD_SOYUZ_SOCHINITELNYJ_SOEDINITELNYJ = 29001;
    CONST RAZRJAD_SOYUZ_SOCHIN_PROTIV = 29002;
    CONST RAZRJAD_SOYUZ_SOCHIN_RAZD = 29003;
    CONST RAZRJAD_SOYUZ_PODCH_VREM = 29004;
    CONST RAZRJAD_SOYUZ_PODCH_PRICH = 29005;
    CONST RAZRJAD_SOYUZ_PODCH_CEL = 29006;
    CONST RAZRJAD_SOYUZ_PODCH_USL = 29007;
    CONST RAZRJAD_SOYUZ_PODCH_UST = 29008;
    CONST RAZRJAD_SOYUZ_PODCH_SRAV = 29009;
    CONST RAZRJAD_SOYUZ_PODCH_SLED = 29010;
    CONST RAZRJAD_SOYUZ_PODCH_IZJASN = 29011;

    const IZMENJAEMOST_PADEZHA_NEIZMENJAEMYJ_PADEZH = 30001;


    const TIP_MESTOIMENIYA_BEZLICHNOE = 31001;
    const TIP_MESTOIMENIYA_LICHNOE_ODUSHEVLENNOE = 31002;
    const TIP_MESTOIMENIYA_MESTOIMENIE_PRILAGATELNOE = 31003;
    const TIP_MESTOIMENIYA_MESTOIMENIE_SUSHHESTVITELNOE = 31004;

    public static $getMorphologyGroupIdCache;
    public static $classes = [];
    public static $map_of_comparisons_morphology = [];
    public static $classByChastRechiAndPriznak = [];

    public static function getLvl1()
    {
        return array_keys(
            static::getClasses()
        );
    }


    /**
     * $brief Метод для оптимизации, возвращающий карту сравнений в виде массива с двумя ключами
     * $array[$key1][$key2] = $value;
     * где $key1 - класс первого элемента, $key2 -  класс второго элемента, $value - совпадение (true) или несовпадение (false);
     *
     * @return bool[][]
     */
    public static function getMapOfComparisonsMorphology()
    {
        // Если поле заполнено, то не пересчитываем его
        if (static::$map_of_comparisons_morphology !== []) {
            return static::$map_of_comparisons_morphology;
        }

        $classes = static::getClasses();

        $result = [];
        foreach ($classes as $level_1) {
            foreach ($level_1 as $level_2) {

                //Формируем все варианты совпадений
                foreach ($level_2 as $index_left => $left) {
                    foreach ($level_2 as $index_right => $right) {
                        $result[$left][$right] = true;
                    }
                }

            }
        }
        static::$map_of_comparisons_morphology = $result;
        return static::$map_of_comparisons_morphology;
    }


    /**
     * @return string[][][]
     */
    public static function getClasses()
    {
        if ([] === static::$classes) {

            static::$classes = [
                static::PADESZH => [
                    static::PADESZH_IMENITELNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Imenitelnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij::class,
                    ],
                    static::PADESZH_RODITELNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Roditelnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Roditelnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij::class,
                    ],
                    static::PADESZH_DATELNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Datelnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Datelnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Datelnij::class,
                    ],
                    static::PADESZH_VINITELNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Vinitelnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Vinitelnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Vinitelnij::class,
                    ],
                    static::PADESZH_TVORITELNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Tvoritelnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Tvoritelnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Tvoritelnij::class,
                    ],
                    static::PADESZH_PREDLOZSHNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Predlozshnij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Predlozshnij::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Predlozshnij::class,
                    ],
                ],
                static::ROD => [
                    static::ROD_ZHENSKII => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskij::class,
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Zhenskij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Zhenskiy::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Zhenskij::class,
                    ],
                    static::ROD_SREDNIJ => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::class,
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Srednij::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Sredniy::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Srednij::class,
                    ],
                    static::ROD_MUZHSKOI => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi::class,
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Muzhskoy::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Muzhskoi::class,
                    ],
                ],
                static::CHISLO => [
                    static::CHISLO_EDINSTVENNOE => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe::class,
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Edinstvennoe::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe::class,
                    ],
                    static::CHISLO_MNOZHESTVENNOE => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Mnozhestvennoe::class,
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::class,
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Mnozhestvennoe::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Mnozhestvennoe::class,
                    ],
                ],
                static::PEREHODNOST => [
                    static::PEREHODNOST_PEREHODNII => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Perehodnyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Perehodnyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Perehodnij::class,
                    ],
                    static::PEREHODNOST_NEPEREHODNII => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Neperehodnyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Neperehodnij::class,
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
                static::RAZRYAD_MESTOIMENIE => [
                    static::RAZRYAD_MESTOIMENIE_OTNOSITELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otnositelnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_PRITYAZHATELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Prityazhatelnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_VOZVRATNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Vozvratnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_LICHNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Lichnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_NEOPREDELENNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Neopredelennoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_OPREDELITELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Opredelitelnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_OTRICATELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otricatelnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_UKAZATELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Ukazatelnoe::class,
                    ],
                    static::RAZRYAD_MESTOIMENIE_VOPROSITELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Voprositelnoe::class,
                    ],
                ],

                static::RAZRYAD_PRILAGATELNOE => [
                    static::RAZRYAD_PRILAGATELNOE_KACHESTVENNOE => [
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Kachestvennoe::class,
                    ],
                    static::RAZRYAD_PRILAGATELNOE_OTNOSITELNOE => [
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Otnositelnoe::class,
                    ],
                    // ????? ya ?= ia
                    static::RAZRYAD_PRILAGATELNOE_PRITYAZHATELNOE => [
                        ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Prityazhatelnoe::class,
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
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Pervoe::class,
                    ],
                    static::LITSO_VTOROE => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Vtoroe::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Vtoroe::class,
                    ],
                    static::LITSO_TRETIE => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::class,
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Tretie::class,
                    ],
                ],
                static::VID => [
                    static::VID_SOVERSHENNYJ => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Sovershennyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Sovershennyj::class,
                    ],
                    static::VID_NESOVERSHENNYJ => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Nesovershennyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Nesovershennyj::class,
                    ],
                ],
                static::VOZVRATNOST => [
                    static::VOZVRATNOST_VOZVRATNYJ => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Vozvratnyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Vozvratnyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Vozvratnyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Vozvratnyj::class,
                    ],
                    static::VOZVRATNOST_NEVOZVRATNYJ => [
                        ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::class,
                        ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj::class,
                        ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Nevozvratnyj::class,
                        ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Nevozvratnyj::class,
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
                static::PODVID_CHISLITELNOGO => [
                    static::PODVID_CHISLITELNOGO_PROSTOY => [
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Prostoy::class
                    ],
                    static::PODVID_CHISLITELNOGO_SOSTAVNOY => [
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Sostavnoy::class
                    ],
                ],
                static::TIP_CHISLITELNOGO => [
                    static::TIP_CHISLITELNOGO_CELIY => [
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Celiy::class
                    ],
                    static::TIP_CHISLITELNOGO_DROBNIY => [
                        ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Drobniy::class
                    ],
                    static::TIP_CHISLITELNOGO_SOBIRATELNIY => [
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

                static::OTGLAGOLNOST_SUSCHESTVITELNOE => [
                    static::OTGLAGOLNOST_SUSCHESTVITELNOE_NEOTGLAGOLNOE => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Neotglagolnoe::class
                    ],

                    static::OTGLAGOLNOST_SUSCHESTVITELNOE_OTGLAGOLNOE => [
                        ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Otglagolnoe::class
                    ],
                ],

                static::TIP_MESTOIMENIYA => [
                    static::TIP_MESTOIMENIYA_BEZLICHNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Tip\Bezlichnoe::class
                    ],
                    static::TIP_MESTOIMENIYA_LICHNOE_ODUSHEVLENNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Tip\LichnoeOdushevlennoe::class
                    ],
                    static::TIP_MESTOIMENIYA_MESTOIMENIE_PRILAGATELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Tip\Prilagatelnoe::class
                    ],
                    static::TIP_MESTOIMENIYA_MESTOIMENIE_SUSHHESTVITELNOE => [
                        ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Tip\Sushhestvitelnoe::class
                    ],
                ]

            ];
        }

        return static::$classes;
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

    public static function getMorphologyGroupId()
    {
        if (null !== static::$getMorphologyGroupIdCache) {
            return static::$getMorphologyGroupIdCache;
        }

        static::$getMorphologyGroupIdCache = [];

        foreach (static::getClasses() as $priznak => $variant_groups) {
            foreach ($variant_groups as $variant_group_id => $variant_group) {
                foreach ($variant_group as $id => $variant) {
                    static::$getMorphologyGroupIdCache[$variant] = $variant_group_id;
                }
            }
        }

        return static::$getMorphologyGroupIdCache;
    }

    public static function getVariantsLvl2()
    {
        $tmp = [];
        foreach (static::getClasses() as $priznak => $variant_groups) {
            foreach ($variant_groups as $variant_group_id => $variant_group) {
                foreach ($variant_group as $id => $variant) {
                    $tmp[$variant] = $variant_group_id;
                }
            }
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

    public static function getIdMorphologyByClass($morphology_class)
    {
        foreach (static::getNullClasses() as $id_morphology => $classes) {
            if (in_array($morphology_class, $classes, true)) {
                return $id_morphology;
            }
        }

        foreach (static::getClasses() as $variants) {
            foreach ($variants as $id_morphology => $classes) {
                if (in_array($morphology_class, $classes, true)) {
                    return $id_morphology;
                }
            }


        }
        return null;
    }

    public static function getNullClasses()
    {
        return [
            static::PADESZH => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\ClassNull::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\ClassNull::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\ClassNull::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\ClassNull::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\ClassNull::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\ClassNull::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\ClassNull::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\ClassNull::class,
            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\ClassNull::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\ClassNull::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\ClassNull::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\ClassNull::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\ClassNull::class,

            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\ClassNull::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\ClassNull::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\ClassNull::class,

            ],
            static::SKLONENIE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::class
            ],
            static::NEIZMENYAJMOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Neizmenyajmost\ClassNull::class
            ],
            static::FORMA => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\ClassNull::class
            ],
            static::ZALOG => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\ClassNull::class,
            ],
            static::RAZRYAD_MESTOIMENIE => [
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\ClassNull::class,
            ],
            static::NAKLONENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\ClassNull::class,
            ],
            static::SPRYAZHENIE => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\ClassNull::class,
            ],
            static::LITSO => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\ClassNull::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\ClassNull::class,
            ],
            static::VID => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\ClassNull::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\ClassNull::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\ClassNull::class,
            ],
            static::VOZVRATNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\ClassNull::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\ClassNull::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\ClassNull::class,
            ],
            static::STEPEN_SRAVNENIYA => [
                ChastiRechiRegistry::NARECHIE => \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\ClassNull::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::class,
            ],
            static::ODUSHEVLYONNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\ClassNull::class,
            ],
            static::VREMYA => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\ClassNull::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\ClassNull::class,
            ],
            static::NARITCATELNOST => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ClassNull::class,
            ],
            static::PODVID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\ClassNull::class,
            ],
            static::TIP_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\ClassNull::class,
            ],
            static::VID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\ClassNull::class,
            ],
            static::RAZRYAD_PRILAGATELNOE => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::class,
            ],
            static::OTGLAGOLNOST_SUSCHESTVITELNOE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\ClassNull::class
            ],
        ];
    }

    public static function getIdMorphologyByBaseClass($morphology_class)
    {
        foreach (static::getBaseClasses() as $id_morphology => $classes) {
            if (in_array($morphology_class, $classes, true)) {
                return $id_morphology;
            }
        }
        return null;
    }

    public static function getBaseClasses()
    {
        return [
            static::PADESZH => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Base::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Base::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Base::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Base::class,
            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Base::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Base::class,
            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Base::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Base::class,
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
            static::RAZRYAD_MESTOIMENIE => [
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
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Base::class,
            ],
            static::VID => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Base::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Base::class,
            ],
            static::VOZVRATNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Base::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Base::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Base::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Base::class,
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
            static::PODVID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Base::class,
            ],
            static::TIP_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Base::class,
            ],
            static::VID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Base::class,
            ],

            static::RAZRYAD_PRILAGATELNOE => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Base::class,
            ],

            static::OTGLAGOLNOST_SUSCHESTVITELNOE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Base::class
            ]
        ];
    }

    public static function getIdAndChastRehiAndMorphologyIdByBaseClass($morphology_class)
    {
        foreach (static::getBaseClasses() as $morphology_id => $classes) {
            foreach ($classes as $chast_rechi_id => $variant) {
                if ($morphology_class === $variant) {
                    return [$chast_rechi_id, $morphology_id];
                }
            }
        }
        return null;
    }

    public static function getClassByChastRechiAndPriznak($chast_rechi_id, $priznak_id_input)
    {
        if (isset(static::$classByChastRechiAndPriznak[$chast_rechi_id][$priznak_id_input])) {
            return static::$classByChastRechiAndPriznak[$chast_rechi_id][$priznak_id_input];
        }

        foreach (static::getClasses() as $priznak_group => $variants) {
            foreach ($variants as $priznak_id => $classes) {
                if ($priznak_id_input === $priznak_id) {
                    if (!empty($classes[$chast_rechi_id])) {
                        return static::$classByChastRechiAndPriznak[$chast_rechi_id][$priznak_id_input] = $classes[$chast_rechi_id];
                    }
                }
            }

        }
        return null;
    }

    /**
     * @param $chast_rechi_class
     * @param $priznak_class_input
     * @return bool
     */
    public static function checkMatchByChastRechiClassAndPriznakClass($chast_rechi_class, $priznak_class_input)
    {
        $chast_rechi_id = ChastiRechiRegistry::getIdByClass($chast_rechi_class);
        foreach (static::getNullClasses() as $priznak_group => $variants) {
            if (!empty($variants[$chast_rechi_id]) && $variants[$chast_rechi_id] === $priznak_class_input) {
                return true;
            }
        }

        foreach (static::getClasses() as $priznak_group => $variants) {
            foreach ($variants as $priznak_id => $classes) {
                if (!empty($classes[$chast_rechi_id]) && $classes[$chast_rechi_id] === $priznak_class_input) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getGroupIdByPriznakClass($priznak_class_input)
    {
        foreach (static::getClasses() as $priznak_group_id => $variants) {
            foreach ($variants as $priznak_id => $classes) {
                foreach ($classes as $chast_rechi_id => $priznak_class) {
                    if ($priznak_class_input === $priznak_class) {
                        return $priznak_group_id;
                    }
                }
            }
        }
        return null;
    }

    public static function getVariantIdByPriznakClass($priznak_class_input)
    {
        foreach (static::getClasses() as $priznak_group_id => $variants) {
            foreach ($variants as $variant_id => $classes) {
                foreach ($classes as $chast_rechi_id => $priznak_class) {
                    if ($priznak_class_input === $priznak_class) {
                        return $variant_id;
                    }
                }
            }
        }
        return null;
    }

    public static function getChastiRechiWithPriznakiWithVarianti()
    {
        $element_template = [
            'id' => null,
            'text' => null,
            'children' => []
        ];
        //Выходной массив частей речи с признаками и вариантами
        $chasti_rechi_array = [];
        //Оббегаем все признаки
        foreach (static::getClasses() as $priznak_id => $variants) {
            foreach ($variants as $variant_id => $variant) {
                foreach ($variant as $chast_rechi_id => $chast_rechi) {
                    $chasti_rechi_array[$chast_rechi_id]['id'] = $chast_rechi_id;
                    $chasti_rechi_array[$chast_rechi_id]['text'] = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getNames()[$chast_rechi_id];

                    $chasti_rechi_array[$chast_rechi_id]['children'][$priznak_id]['id'] = $priznak_id;
                    $chasti_rechi_array[$chast_rechi_id]['children'][$priznak_id]['text'] = static::getNames()[$priznak_id];

                    $element_template2 = $element_template;
                    $element_template2['id'] = $variant_id;
                    $element_template2['text'] = static::getNames()[$variant_id];
                    $chasti_rechi_array[$chast_rechi_id]['children'][$priznak_id]['children'][$variant_id] = $element_template2;
                }
            }
        }

        //изменение ассоциативности:
        $chasti_rechi_array_no_associations = [];
        $k = 0;
        foreach ($chasti_rechi_array as $chasti_rechi) {
            $k = $chasti_rechi['id'];
            $chasti_rechi_array_no_associations[$k][0]['id'] = $chasti_rechi['id'];
            $chasti_rechi_array_no_associations[$k][0]['text'] = $chasti_rechi['text'];
            $chasti_rechi_array_no_associations[$k][0]['children'] = [];
            $l = 0;
            foreach ($chasti_rechi['children'] as $priznak) {
                $chasti_rechi_array_no_associations[$k][0]['children'][]['id'] = $priznak['id'];
                $chasti_rechi_array_no_associations[$k][0]['children'][$l]['text'] = $priznak['text'];
                $chasti_rechi_array_no_associations[$k][0]['children'][$l]['children'] = [];

                $m = 0;
                foreach ($priznak['children'] as $variant) {
                    $chasti_rechi_array_no_associations[$k][0]['children'][$l]['children'][]['id'] = $variant['id'];
                    $chasti_rechi_array_no_associations[$k][0]['children'][$l]['children'][$m]['text'] = $variant['text'];
                    $chasti_rechi_array_no_associations[$k][0]['children'][$l]['children'][$m]['children'] = [];
                    $m++;
                }
                $l++;
            }
//            $k++;
        }
        return $chasti_rechi_array_no_associations;
    }

    public static function getNames()
    {
        $names = [
            static::PADESZH_IMENITELNIJ => 'именительный падеж',
            static::PADESZH_RODITELNIJ => 'родительный падеж',
            static::PADESZH_DATELNIJ => 'дательный падеж',
            static::PADESZH_VINITELNIJ => 'винительный падеж',
            static::PADESZH_TVORITELNIJ => 'творительный падеж',
            static::PADESZH_PREDLOZSHNIJ => 'предложный падеж',
            static::ROD_MUZHSKOI => 'мужской род',
            static::ROD_ZHENSKII => 'женский род',
            static::ROD_SREDNIJ => 'средний род',
            static::CHISLO_EDINSTVENNOE => 'единственное число',
            static::CHISLO_MNOZHESTVENNOE => 'множественное число',

            static::SKLONENIE_PERVOE => 'первое склонение',
            static::SKLONENIE_VTOROE => 'второе склонение',
            static::SKLONENIE_TRETIE => 'третье склонение',
            static::NEIZMENYAJMOST_IZMNYAJMIJ => 'неизменяемый',
            static::NEIZMENYAJMOST_NEIZMNYAJMIJ => 'изменяемый',
            static::PEREHODNOST_PEREHODNII => 'переходный',
            static::PEREHODNOST_NEPEREHODNII => 'непереходный',
            static::RAZRYAD_PRILAGATELNOE_KACHESTVENNOE => 'качественное',
            static::RAZRYAD_MESTOIMENIE_OTNOSITELNOE => 'относительное',
            static::RAZRYAD_MESTOIMENIE_PRITYAZHATELNOE => 'притяжательное',
            static::RAZRYAD_MESTOIMENIE_VOZVRATNOE => 'возвратное',
            static::RAZRYAD_MESTOIMENIE_LICHNOE => 'личное',
            static::RAZRYAD_MESTOIMENIE_NEOPREDELENNOE => 'неопределенное',
            static::RAZRYAD_MESTOIMENIE_OPREDELITELNOE => 'определительное',
            static::RAZRYAD_MESTOIMENIE_OTRICATELNOE => 'отрицательное',
            static::RAZRYAD_MESTOIMENIE_UKAZATELNOE => 'указательное',
            static::RAZRYAD_MESTOIMENIE_VOPROSITELNOE => 'вопросительное',

            static::FORMA_POLNAYA => 'полная',
            static::FORMA_KRATKAYA => 'краткая',

            static::STEPEN_SRAVNENIYA_POLOZHITELNAYA => 'положительная',
            static::STEPEN_SRAVNENIYA_SRAVNITELNAYA => 'сравнительная',
            static::STEPEN_SRAVNENIYA_PREVOSHODNAYA => 'превосходная',

            static::VID_SOVERSHENNYJ => 'совершенный',
            static::VID_NESOVERSHENNYJ => 'несовершенный',
            static::VOZVRATNOST_VOZVRATNYJ => 'возвратный',
            static::VOZVRATNOST_NEVOZVRATNYJ => 'невозвратный',

            static::ZALOG_DEJSTVITELNYJ => 'действительный',
            static::ZALOG_STRADATELNYJ => 'страдательный',

            static::SPRYAZHENIE_PERVOE => 'первое',
            static::SPRYAZHENIE_VTOROE => 'второе',

            static::NAKLONENIE_IZYAVITELNOE => 'изъявительное',
            static::NAKLONENIE_POVELITELNOE => 'повелительное',
            static::NAKLONENIE_YSLOVNOE => 'условное',


            static::VREMYA_BUDUSCHEE => 'будущее',
            static::VREMYA_NASTOYASCHEE => 'настоящее',
            static::VREMYA_PROSHEDSHEE => 'прошедшее',

            static::LITSO_PERVOE => 'первое',
            static::LITSO_VTOROE => 'второе',
            static::LITSO_TRETIE => 'третье',
            static::ODUSHEVLYONNOST_ODUSHEVLYONNOE => 'одушевленное',
            static::ODUSHEVLYONNOST_NEODUSHEVLYONNOE => 'неодушевленное',
            static::NARITCATELNOST_IMIA_NARITCATELNOE => 'нарицательное',
            static::NARITCATELNOST_IMIA_SOBSTVENNOE => 'собственное',

            static::PODVID_CHISLITELNOGO_PROSTOY => 'простое',
            static::PODVID_CHISLITELNOGO_SOSTAVNOY => 'составное',
            static::TIP_CHISLITELNOGO_CELIY => 'целое',
            static::TIP_CHISLITELNOGO_DROBNIY => 'дробное',
            static::TIP_CHISLITELNOGO_SOBIRATELNIY => 'собирательное',
            static::VID_CHISLITELNOGO_KOLICHESTVENNIY => 'количественный',
            static::VID_CHISLITELNOGO_PORYADKOVIY => 'порядковый',

            static::RAZRYAD_PRILAGATELNOE_KACHESTVENNOE => 'качественное',
            static::RAZRYAD_PRILAGATELNOE_OTNOSITELNOE => 'относительное',
            static::RAZRYAD_PRILAGATELNOE_PRITYAZHATELNOE => 'притяжательное',

            static::OTGLAGOLNOST_SUSCHESTVITELNOE_NEOTGLAGOLNOE => 'неотглагольное',
            static::OTGLAGOLNOST_SUSCHESTVITELNOE_OTGLAGOLNOE => 'отглагольное',

            static::TIP_MESTOIMENIYA_BEZLICHNOE => 'безличное местоимение',
            static::TIP_MESTOIMENIYA_LICHNOE_ODUSHEVLENNOE => 'личное одушевленное местоимение',
            static::TIP_MESTOIMENIYA_MESTOIMENIE_PRILAGATELNOE => 'прилагательное местоимение',
            static::TIP_MESTOIMENIYA_MESTOIMENIE_SUSHHESTVITELNOE => 'существительное местоимение',
        ];


        return $names + parent::getNames();
    }

    public static function new_old()
    {
        $names = [
            static::PADESZH_IMENITELNIJ => \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID,
            static::PADESZH_RODITELNIJ => \Aot\MivarTextSemantic\Constants::CASE_GENITIVE_ID,
            static::PADESZH_DATELNIJ => \Aot\MivarTextSemantic\Constants::CASE_DATIVE_ID,
            static::PADESZH_VINITELNIJ => \Aot\MivarTextSemantic\Constants::CASE_ACCUSATIVE_ID,
            static::PADESZH_TVORITELNIJ => \Aot\MivarTextSemantic\Constants::CASE_INSTRUMENTAL_ID,
            static::PADESZH_PREDLOZSHNIJ => \Aot\MivarTextSemantic\Constants::CASE_PREPOSITIONAL_ID,
            static::ROD_MUZHSKOI => \Aot\MivarTextSemantic\Constants::GENUS_MASCULINE_ID,
            static::ROD_ZHENSKII => \Aot\MivarTextSemantic\Constants::GENUS_FEMININE_ID,
            static::ROD_SREDNIJ => \Aot\MivarTextSemantic\Constants::GENUS_NEUTER_ID,
            static::CHISLO_EDINSTVENNOE => \Aot\MivarTextSemantic\Constants::NUMBER_SINGULAR_ID,
            static::CHISLO_MNOZHESTVENNOE => \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID,
            static::SKLONENIE_PERVOE => \Aot\MivarTextSemantic\OldAotConstants::DECLENSION_1,
            static::SKLONENIE_VTOROE => \Aot\MivarTextSemantic\OldAotConstants::DECLENSION_2,
            static::SKLONENIE_TRETIE => \Aot\MivarTextSemantic\OldAotConstants::DECLENSION_3,
            static::SKLONENIE_NESKLONJAEMOE => 31,
//            static::NEIZMENYAJMOST_IZMNYAJMIJ => 'неизменяемый',
//            static::NEIZMENYAJMOST_NEIZMNYAJMIJ => 'изменяемый',
            static::PEREHODNOST_PEREHODNII => \Aot\MivarTextSemantic\OldAotConstants::TRANSITIVE,
            static::PEREHODNOST_NEPEREHODNII => \Aot\MivarTextSemantic\OldAotConstants::INTRANSITIVE,
            static::RAZRYAD_PRILAGATELNOE_KACHESTVENNOE => \Aot\MivarTextSemantic\OldAotConstants::QUALIFYING_ADJECTIVE,
            static::RAZRYAD_MESTOIMENIE_OTNOSITELNOE => \Aot\MivarTextSemantic\OldAotConstants::RELATIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_PRITYAZHATELNOE => \Aot\MivarTextSemantic\OldAotConstants::POSSESSIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_VOZVRATNOE => \Aot\MivarTextSemantic\OldAotConstants::REFLEXIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_LICHNOE => \Aot\MivarTextSemantic\OldAotConstants::PERSONAL_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_NEOPREDELENNOE => \Aot\MivarTextSemantic\OldAotConstants::INDEFINITE_PRONOUN,
            // ?
            static::RAZRYAD_MESTOIMENIE_OPREDELITELNOE => \Aot\MivarTextSemantic\OldAotConstants::ATTRIBUTIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_OTRICATELNOE => \Aot\MivarTextSemantic\OldAotConstants::NEGATIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_UKAZATELNOE => \Aot\MivarTextSemantic\OldAotConstants::DEMONSTRATIVE_PRONOUN,
            static::RAZRYAD_MESTOIMENIE_VOPROSITELNOE => \Aot\MivarTextSemantic\OldAotConstants::INTERROGATIVE_PRONOUN,
            static::FORMA_POLNAYA => \Aot\MivarTextSemantic\OldAotConstants::SHORT_WORD_FORM,
            static::FORMA_KRATKAYA => \Aot\MivarTextSemantic\OldAotConstants::FULL_WORD_FORM,
            static::STEPEN_SRAVNENIYA_POLOZHITELNAYA => \Aot\MivarTextSemantic\OldAotConstants::POSITIVE_DEGREE_COMPARISON,
            static::STEPEN_SRAVNENIYA_SRAVNITELNAYA => \Aot\MivarTextSemantic\OldAotConstants::COMPARATIVE_DEGREE_COMPARISON,
            static::STEPEN_SRAVNENIYA_PREVOSHODNAYA => \Aot\MivarTextSemantic\Constants::DEGREE_SUPERLATIVE_ID,
            static::VID_SOVERSHENNYJ => \Aot\MivarTextSemantic\Constants::VIEW_PERFECTIVE_ID,
            static::VID_NESOVERSHENNYJ => \Aot\MivarTextSemantic\Constants::VIEW_IMPERFECT_ID,
            static::VOZVRATNOST_VOZVRATNYJ => \Aot\MivarTextSemantic\OldAotConstants::RETRIEVABLE,
            static::VOZVRATNOST_NEVOZVRATNYJ => \Aot\MivarTextSemantic\OldAotConstants::IRRETRIEVABLE,
            static::ZALOG_DEJSTVITELNYJ => \Aot\MivarTextSemantic\Constants::COMMUNION_VALID_ID,
            static::ZALOG_STRADATELNYJ => \Aot\MivarTextSemantic\Constants::COMMUNION_PASSIVE_ID,
            static::SPRYAZHENIE_PERVOE => \Aot\MivarTextSemantic\OldAotConstants::CONJUGATION_1,
            static::SPRYAZHENIE_VTOROE => \Aot\MivarTextSemantic\OldAotConstants::CONJUGATION_2,
            static::NAKLONENIE_IZYAVITELNOE => \Aot\MivarTextSemantic\OldAotConstants::MOOD_INDICATIVE,
            static::NAKLONENIE_POVELITELNOE => \Aot\MivarTextSemantic\OldAotConstants::MOOD_IMPERATIVE,
            static::NAKLONENIE_YSLOVNOE => \Aot\MivarTextSemantic\OldAotConstants::MOOD_SUBJUNCTIVE,
            static::VREMYA_BUDUSCHEE => \Aot\MivarTextSemantic\Constants::TIME_FUTURE_ID,
            static::VREMYA_NASTOYASCHEE => \Aot\MivarTextSemantic\Constants::TIME_SIMPLE_ID,
            static::VREMYA_PROSHEDSHEE => \Aot\MivarTextSemantic\Constants::TIME_PAST_ID,
            static::LITSO_PERVOE => \Aot\MivarTextSemantic\Constants::PERSON_RIFST_ID,
            static::LITSO_VTOROE => \Aot\MivarTextSemantic\Constants::PERSON_SECOND_ID,
            static::LITSO_TRETIE => \Aot\MivarTextSemantic\Constants::PERSON_THIRD_ID,
            static::ODUSHEVLYONNOST_ODUSHEVLYONNOE => \Aot\MivarTextSemantic\Constants::ANIMALITY_ANIMATE_ID,
            static::ODUSHEVLYONNOST_NEODUSHEVLYONNOE => \Aot\MivarTextSemantic\Constants::ANIMALITY_INANIMATE_ID,
            static::NARITCATELNOST_IMIA_NARITCATELNOE => \Aot\MivarTextSemantic\OldAotConstants::NOMINAL,
            static::NARITCATELNOST_IMIA_SOBSTVENNOE => \Aot\MivarTextSemantic\OldAotConstants::SELF,


            static::VID_CHISLITELNOGO_KOLICHESTVENNIY => \Aot\MivarTextSemantic\Constants::QUANTITATIVE_ID,
            static::VID_CHISLITELNOGO_PORYADKOVIY => \Aot\MivarTextSemantic\Constants::ORDINAL_ID,

            static::RAZRYAD_PRILAGATELNOE_KACHESTVENNOE => 38,
            static::RAZRYAD_PRILAGATELNOE_OTNOSITELNOE => 39,
            static::RAZRYAD_PRILAGATELNOE_PRITYAZHATELNOE => 40,

            static::OTGLAGOLNOST_SUSCHESTVITELNOE_NEOTGLAGOLNOE => \Aot\MivarTextSemantic\Constants::NEOTGLAGOLNOE,
            static::OTGLAGOLNOST_SUSCHESTVITELNOE_OTGLAGOLNOE => \Aot\MivarTextSemantic\Constants::OTGLAGOLNOE,


            static::PODVID_CHISLITELNOGO_PROSTOY => 82,
            static::PODVID_CHISLITELNOGO_SOSTAVNOY => 83,
            static::PODVID_CHISLITELNOGO_SLOZHNOE => 84,

            static::TIP_CHISLITELNOGO_CELIY => 87,
            static::TIP_CHISLITELNOGO_DROBNIY => 86,
            static::TIP_CHISLITELNOGO_SOBIRATELNIY => 85,

            // update value_attribute set id_value_attr = 84 where id_value_attr = 1073;


            static::PROIZVODNOST_PREDLOG_PROIZVODNIJ => 57,
            static::PROIZVODNOST_PREDLOG_NEPROIZVODNIJ => 58,


            static:: RAZRJAD_PREDLOG_PROSTRANSTVENNYJ => 59,
            static:: RAZRJAD_PREDLOG_VREMENNOJ => 60,
            static:: RAZRJAD_PREDLOG_PRICHINNYJ => 61,
            static:: RAZRJAD_PREDLOG_CELEVOJ => 62,
            static:: RAZRJAD_PREDLOG_OBRAZA_DEJSTVIJA => 63,
            static:: RAZRJAD_PREDLOG_DOPOLNITELNYJ => 64,


            static::RAZRJAD_SOYUZ_SOCHINITELNYJ_SOEDINITELNYJ => 65,
            static::RAZRJAD_SOYUZ_SOCHIN_PROTIV => 67,
            static::RAZRJAD_SOYUZ_SOCHIN_RAZD => 68,
            static::RAZRJAD_SOYUZ_PODCH_VREM => 90,
            static::RAZRJAD_SOYUZ_PODCH_PRICH => 91,
            static::RAZRJAD_SOYUZ_PODCH_CEL => 92,
            static::RAZRJAD_SOYUZ_PODCH_USL => 93,
            static::RAZRJAD_SOYUZ_PODCH_UST => 94,
            static::RAZRJAD_SOYUZ_PODCH_SRAV => 95,
            static::RAZRJAD_SOYUZ_PODCH_SLED => 96,
            static::RAZRJAD_SOYUZ_PODCH_IZJASN => 97,

            static::IZMENJAEMOST_PADEZHA_NEIZMENJAEMYJ_PADEZH => 1,
            //static::TIP_CHISLITELNOGO_LICHNOE_ODUSHEVLENNOE => 75,

            static::TIP_MESTOIMENIYA_BEZLICHNOE => 72,
            static::TIP_MESTOIMENIYA_LICHNOE_ODUSHEVLENNOE => 70,
            static::TIP_MESTOIMENIYA_MESTOIMENIE_PRILAGATELNOE => 69,
            static::TIP_MESTOIMENIYA_MESTOIMENIE_SUSHHESTVITELNOE => 71,

            static::RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_MESTA => 66,
            static::RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_VREMENI => 75,
            static::RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_PRICHINY => 76,
            static::RAZRYAD_NARECHIE_OBSTOJATELSTVENNYJ_CELI => 77,


            static::RAZRYAD_NARECHIE_OPREDELITELNYJ_OBRAZA_DEJSTVIJA => 88,
            static::RAZRYAD_NARECHIE_OPREDELITELNYJ_MERY_I_STEPENI => 89,
        ];

        return $names;
    }


    /*public static function getPriznakClass_PriznakId_PriznakBaseClass_ChastRechiId()
    {
        $base_classes = static::getBaseClasses();

        $res = [];
        foreach (static::getClasses() as $priznak_group_id => $variants) {

            foreach ($variants as $variant_id => $classes) {

                foreach ($classes as $chast_rechi_id => $priznak_class) {

                    if (empty($base_classes[$priznak_group_id][$chast_rechi_id])) {
                        throw new \LogicException("wtf: priznak_group_id = $priznak_group_id and chast_rechi_id = $chast_rechi_id");
                    }

                    //$base_class = $base_classes[$priznak_group_id][$chast_rechi_id];

                    //$res[$variant_id][$base_class][$chast_rechi_id] = $priznak_class;
                    $res[$variant_id][$chast_rechi_id] = $priznak_class;
                }
            }
        }

        return $res;

    }*/

    /**
     * @param \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] $priznaki
     * @return int[]
     */
    public static function getPriznakIdsByClasses(array $priznaki)
    {
        foreach ($priznaki as $priznak) {
            assert(is_a($priznak, \Aot\RussianMorphology\ChastiRechi\MorphologyBase::class, true));
        }

        $res = [];
        foreach ($priznaki as $priznak) {
            $res[] = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantIdByPriznakClass(get_class($priznak));
        }
        return $res;
    }
}