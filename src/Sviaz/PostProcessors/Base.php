<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 27.07.2015
 * Time: 17:17
 */

namespace Aot\Sviaz\PostProcessors;


abstract class Base
{
    public static function create()
    {
        return new static();
    }

    abstract public function run($sviazi);
}