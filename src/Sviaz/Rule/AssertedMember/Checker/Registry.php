<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:22
 */

namespace Aot\Sviaz\Rule\AssertedMember\Checker;


class Registry
{
    const PredlogPeredSlovom = 1;
    const ChasticaNePeredSlovom = 2;

    public static function getClasses()
    {
        return [
            static::PredlogPeredSlovom => \Aot\Sviaz\Rule\AssertedMember\Checker\PredlogPeredSlovom::class,
            static::ChasticaNePeredSlovom => \Aot\Sviaz\Rule\AssertedMember\Checker\ChasticaNePeredSlovom::class,
        ];
    }

    public static function getNames()
    {
        return [
            static::PredlogPeredSlovom => 'предлог перед словом'
        ];
    }
}