<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:16
 */

namespace Aot\RussianMorphology\ChastiRechi;


use Aot\Registry\Uploader;

class ChastiRechiRegistry
{
    use Uploader;

    const SUSCHESTVITELNOE = 10;
    const PRILAGATELNOE = 11;
    const GLAGOL = 12;
    const NARECHIE = 13;
    const PRICHASTIE = 14;
    const DEEPRICHASTIE = 15;
    const CHISLITELNOE = 16;
    const MESTOIMENIE = 17;
    const INFINITIVE = 18;

    const PREDLOG = 19;
    const SOYUZ = 20;
    const CHASTICA = 21;

    const MEZHDOMETIE = 22;
    const PRISTAVKA = 23;

    CONST SOKRASHHENIE = 24;
    CONST ABBREVIATURA = 25;

    CONST FRAZ = 29;


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
            static::INFINITIVE => 'инфинитив',

            static::PREDLOG => 'предлог',
            static::SOYUZ => 'союз',
            static::CHASTICA => 'частица',

            static::MEZHDOMETIE => 'междометие',
            static::PRISTAVKA => 'приставка',

            static::FRAZ => 'фразеологизм',
        ];
    }

    public static function getClasses()
    {
        return [
            static::SUSCHESTVITELNOE => Suschestvitelnoe\Base::class,
            static::PRILAGATELNOE => Prilagatelnoe\Base::class,
            static::GLAGOL => Glagol\Base::class,
            static::PRICHASTIE => Prichastie\Base::class,
            static::NARECHIE => Narechie\Base::class,
            static::DEEPRICHASTIE => Deeprichastie\Base::class,
            static::MESTOIMENIE => Mestoimenie\Base::class,
            static::INFINITIVE => Infinitive\Base::class,
            static::CHISLITELNOE => Chislitelnoe\Base::class,

            static::PREDLOG => Predlog\Base::class,
            static::SOYUZ => Soyuz\Base::class,
            static::CHASTICA => Chastica\Base::class,

            static::MEZHDOMETIE => Mezhdometie\Base::class,
            static::PRISTAVKA => Pristavka\Base::class,

            static::FRAZ => Other\Base::class,
        ];
    }

    /**
     * @return \Aot\RussianMorphology\FactoryBase[]
     */
    public static function getFactories()
    {
        return [
            static::SUSCHESTVITELNOE => Suschestvitelnoe\Factory::get(),
            static::PRILAGATELNOE => Prilagatelnoe\Factory::get(),
            static::GLAGOL => Glagol\Factory::get(),
            static::PRICHASTIE => Prichastie\Factory::get(),
            static::NARECHIE => Narechie\Factory::get(),
            static::DEEPRICHASTIE => Deeprichastie\Factory::get(),
            static::MESTOIMENIE => Mestoimenie\Factory::get(),
            static::INFINITIVE => Infinitive\Factory::get(),
            static::CHISLITELNOE => Chislitelnoe\Factory::get(),
            static::CHASTICA => Chastica\Factory::get(),
            static::PREDLOG => Predlog\Factory::get(),
            static::SOYUZ => Soyuz\Factory::get(),

            static::MEZHDOMETIE => Mezhdometie\Factory::get(),

            static::FRAZ => Other\Factory::get(),
            //static::PRISTAVKA => Pristavka\Factory::get(),
        ];
    }

    /**
     * @param $class
     * @return int | null
     */
    public static function getIdByClass($class)
    {
        $key = array_search($class, static::getClasses(), true);

        if ($key !== false) {
            return $key;
        }

        return null;
    }

    /**
     * @param $class
     * @return int | null
     */
    public static function getIdByMockClass($class)
    {
        foreach (static::getClasses() as $id => $class_name) {
            if (is_a($class, $class_name, true)) {
                return $id;
            }
        }
        return null;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        $x = 1;
        return \SemanticPersistence\Entities\MisotEntities\ChastiRechi::class;
    }

    /**
     * @return int[]
     */
    protected function getIds()
    {
        $x = 1;
        return array_keys(static::getNames());
    }

    /**
     * @return string[]
     */
    protected function getFields()
    {
        $x = 1;
        return [
            'name' => [static::class, 'getNames'],
        ];
    }


    public static function new_old()
    {
        return [
            static::SUSCHESTVITELNOE => \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
            static::PRILAGATELNOE => \Aot\MivarTextSemantic\Constants::ADJECTIVE_CLASS_ID,
            static::GLAGOL => \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
            static::PRICHASTIE => \Aot\MivarTextSemantic\Constants::COMMUNION_CLASS_ID,
            static::NARECHIE => \Aot\MivarTextSemantic\Constants::ADVERB_CLASS_ID,

            static::DEEPRICHASTIE => \Aot\MivarTextSemantic\Constants::PARTICIPLE_CLASS_ID,
            static::CHISLITELNOE => \Aot\MivarTextSemantic\Constants::NUMERAL_CLASS_ID,
            static::MESTOIMENIE => \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID,
            static::INFINITIVE => \Aot\MivarTextSemantic\Constants::INFINITIVE_CLASS_ID,

            static::PREDLOG => \Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID,
            static::SOYUZ => \Aot\MivarTextSemantic\Constants::UNION_CLASS_ID,
            static::CHASTICA => \Aot\MivarTextSemantic\Constants::PARTICLE_CLASS_ID,

            static::MEZHDOMETIE => \Aot\MivarTextSemantic\Constants::INTERJECTION_CLASS_ID,
            static::PRISTAVKA => 16,


            static::SOKRASHHENIE => 15,
            static::ABBREVIATURA => 7
        ];
    }

}