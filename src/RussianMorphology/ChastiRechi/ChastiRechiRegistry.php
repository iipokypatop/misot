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
        ];
    }

    /**
     * @return \Aot\RussianMorphology\Factory[]
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
        return \SemanticPersistence\Entities\MisotEntities\ChastiRechi::class;
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
}