<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:19
 */

namespace Aot\Sviaz\Rule\AssertedLink\Checker;


class Registry
{
    const NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim = 1;

    public static function getClasses()
    {
        return [
            static::NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim => NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim::class
        ];
    }
}