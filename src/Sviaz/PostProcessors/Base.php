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

    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    abstract public function run(array $sviazi);
}