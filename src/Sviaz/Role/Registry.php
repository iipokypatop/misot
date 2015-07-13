<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 17:12
 */

namespace Aot\Sviaz\Role;


class Registry
{
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
}