<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:51
 */

namespace Aot\RussianMorphology\ChastiRechi;


use Aot\Registry\Uploader;

class MorphologyRegistryParent
{
    use Uploader;

    const PADESZH = 1000;
    const ROD = 2000;
    const CHISLO = 3000;
    const SKLONENIE = 4000;
    const NEIZMENYAJMOST = 5000;
    const PEREHODNOST = 6000;
    const NARICATELNOST = 7000;
    const RAZRYAD_MESTOIMENIE = 9000;

    const FORMA = 10000;
    const STEPEN_SRAVNENIYA = 11000;
    const VID = 12000;
    const VOZVRATNOST = 13000;
    const ZALOG = 14000;
    const SPRYAZHENIE = 15000;
    const NAKLONENIE = 16000;
    const VREMYA = 17000;
    const LITSO = 18000;
    const ODUSHEVLYONNOST = 19000;
    const NARITCATELNOST = 20000;
    const PODVID_CHISLITELNOGO = 21000;
    const TIP_CHISLITELNOGO = 22000;
    const VID_CHISLITELNOGO = 23000;

    const RAZRYAD_PRILAGATELNOE = 24000;
    const OTGLAGOLNOST_SUSCHESTVITELNOE = 25000;

    public static function getNames()
    {
        return [
            static::PADESZH => 'падеж',
            static::ROD => 'род',
            static::CHISLO => 'число',
            static::SKLONENIE => 'склонение',
            static::NEIZMENYAJMOST => 'изменяемость',
            static::PEREHODNOST => 'переходность',
            static::RAZRYAD_MESTOIMENIE => 'разряд метоимение',
            static::FORMA => 'форма',
            static::STEPEN_SRAVNENIYA => 'степень сравнения',
            static::VID => 'вид',
            static::VOZVRATNOST => 'возвратность',
            static::ZALOG => 'залог',
            static::SPRYAZHENIE => 'спряжение',
            static::NAKLONENIE => 'наклонение',
            static::VREMYA => 'время',
            static::LITSO => 'лицо',
            static::ODUSHEVLYONNOST => 'одушевленность',
            static::NARITCATELNOST => 'нарицательность',
            static::PODVID_CHISLITELNOGO => 'подвид',
            static::TIP_CHISLITELNOGO => 'тип',
            static::VID_CHISLITELNOGO => 'вид',
            static::RAZRYAD_PRILAGATELNOE => 'разряд прилагательного',
            static::OTGLAGOLNOST_SUSCHESTVITELNOE => 'отглагольность существительного',
        ];
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
            static::RAZRYAD_PRILAGATELNOE => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Base::class,
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
            static::OTGLAGOLNOST_SUSCHESTVITELNOE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Base::class
            ]
        ];
    }


    public static function getNullClasses()
    {
        return [
            static::PADESZH => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\Null::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null::class,
            ],
            static::ROD => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\Null::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Null::class,
            ],
            static::CHISLO => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\Null::class,
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Null::class,

            ],
            static::PEREHODNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Null::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Null::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Null::class,
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
            static::RAZRYAD_MESTOIMENIE => [
                ChastiRechiRegistry::MESTOIMENIE => \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null::class,
            ],
            static::RAZRYAD_PRILAGATELNOE => [
                ChastiRechiRegistry::PRILAGATELNOE => \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::class,
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
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Null::class,
            ],
            static::VOZVRATNOST => [
                ChastiRechiRegistry::GLAGOL => \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::class,
                ChastiRechiRegistry::PRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Null::class,
                ChastiRechiRegistry::DEEPRICHASTIE => \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null::class,
                ChastiRechiRegistry::INFINITIVE => \Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Null::class,
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
            static::PODVID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\Null::class,
            ],
            static::TIP_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\Null::class,
            ],
            static::VID_CHISLITELNOGO => [
                ChastiRechiRegistry::CHISLITELNOE => \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\Null::class,
            ],
            static::OTGLAGOLNOST_SUSCHESTVITELNOE => [
                ChastiRechiRegistry::SUSCHESTVITELNOE => \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Null::class
            ]
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \SemanticPersistence\Entities\MisotEntities\Morphology::class;
    }

    /**
     * @return int[]
     */
    protected function getIds()
    {
        return array_keys(static::getNames());
    }

    /**
     * @return string[]
     */
    protected function getFields()
    {
        return [
            'name' => [static::class, 'getNames'],
        ];
    }

    public static function new_old()
    {
        return [
            static::PADESZH => \Aot\MivarTextSemantic\Constants::CASE_ID,
            static::ROD => \Aot\MivarTextSemantic\Constants::GENUS_ID,
            static::CHISLO => \Aot\MivarTextSemantic\Constants::NUMBER_ID,
            static::SKLONENIE => \Aot\MivarTextSemantic\OldAotConstants::DECLENSION,
            //static::NEIZMENYAJMOST => 'изменяемость',
            static::PEREHODNOST => \Aot\MivarTextSemantic\Constants::TRANSIVITY_ID,

            static::RAZRYAD_MESTOIMENIE => 18,

            static::FORMA => \Aot\MivarTextSemantic\OldAotConstants::WORD_FORM,
            static::STEPEN_SRAVNENIYA => \Aot\MivarTextSemantic\Constants::DEGREE_COMPOSITION_ID,
            static::VID => \Aot\MivarTextSemantic\Constants::VIEW_ID,
            static::VOZVRATNOST => \Aot\MivarTextSemantic\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE,

            static::ZALOG => \Aot\MivarTextSemantic\Constants::DISCHARGE_COMMUNION_ID,

            static::SPRYAZHENIE => \Aot\MivarTextSemantic\OldAotConstants::CONJUGATION,
            static::NAKLONENIE => \Aot\MivarTextSemantic\Constants::MOOD_ID,
            static::VREMYA => \Aot\MivarTextSemantic\Constants::TIME_ID,
            static::LITSO => \Aot\MivarTextSemantic\Constants::PERSON_ID,
            static::ODUSHEVLYONNOST => \Aot\MivarTextSemantic\Constants::ANIMALITY_ID,
            static::NARITCATELNOST => \Aot\MivarTextSemantic\OldAotConstants::SELF_NOMINAL,
            static::PODVID_CHISLITELNOGO => 902,
            static::TIP_CHISLITELNOGO => 903, //????
            static::VID_CHISLITELNOGO => \Aot\MivarTextSemantic\Constants::TYPE_OF_NUMERAL_ID,

            static::RAZRYAD_PRILAGATELNOE => 14,
            static::OTGLAGOLNOST_SUSCHESTVITELNOE => 28,
        ];
    }

    /**
     * @param $base_class_input
     * @return
     */
    public static function getNullClassByBaseClass($base_class_input)
    {
        assert(is_string($base_class_input));

        foreach (static::getBaseClasses() as $base_class_id => $chasti_rechi) {
            foreach ($chasti_rechi as $chasti_rechi_id => $base_class) {
                if($base_class === $base_class_input){
                    return static::getNullClasses()[$base_class_id][$chasti_rechi_id];
                }
            }
        }

        throw new \LogicException("incorrect base_class " . var_export($base_class_input, true));
    }
}
