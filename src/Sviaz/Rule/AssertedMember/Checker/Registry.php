<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:22
 */

namespace Aot\Sviaz\Rule\AssertedMember\Checker;


use Aot\Registry\Uploader;

class Registry
{
    use Uploader;

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
            static::PredlogPeredSlovom => 'предлог перед словом',
            static::ChasticaNePeredSlovom => 'частица НЕ перед словом'
        ];
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\MemberChecker::class;
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