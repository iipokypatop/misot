<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 14:28
 */

namespace Aot\Sviaz;


class Registry
{

    const SOGLASOVANIE = 1;
    const UPRAVLENIE = 2;
    const PRIMIKANIE = 3;


    public static function getClasses()
    {
        return [
            static::SOGLASOVANIE => \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class,
            static::UPRAVLENIE => \Aot\Sviaz\Podchinitrelnaya\Upravlenie::class,
            static::PRIMIKANIE => \Aot\Sviaz\Podchinitrelnaya\Primikanie::class,
        ];
    }


    public static function getNames()
    {
        return [
            static::SOGLASOVANIE => "Согласование",
            static::UPRAVLENIE => "Управление",
            static::PRIMIKANIE => "Примыкание",
        ];
    }

}