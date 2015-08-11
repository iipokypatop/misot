<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 17:12
 */

namespace Aot\Sviaz\Role;


use Aot\Registry\Uploader;

class Registry
{
    use Uploader;

    const OTNOSHENIE = 1;
    const VESCH = 2;
    const SVOISTVO = 3;

    public static function getNames()
    {
        return [
            static::OTNOSHENIE => 'отношение',
            static::VESCH => "вещь",
            static::SVOISTVO => "свойство",
        ];
    }

    public static function getClasses()
    {
        return [
            static::OTNOSHENIE => \Aot\Sviaz\Role\Otnoshenie::class,
            static::VESCH => \Aot\Sviaz\Role\Vesch::class,
            static::SVOISTVO => \Aot\Sviaz\Role\Svoistvo::class,
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\Role::class;
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
        return[
            'name' => [static::class, 'getNames'],
        ];
    }
}