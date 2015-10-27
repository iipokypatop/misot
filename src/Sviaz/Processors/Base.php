<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 023, 23.10.2015
 * Time: 15:12
 */

namespace Aot\Sviaz\Processors;


abstract class Base
{
    abstract public function run(\Aot\Sviaz\Sequence $sequence, array $rules);

    public static function create()
    {
        return new static();
    }
}