<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:32
 */

namespace Aot\Sviaz\Preprocessors;


abstract class Base
{
    abstract public function run(\Aot\Sviaz\Sequence $sequence);

    public static function create()
    {
        return new static();
    }
}