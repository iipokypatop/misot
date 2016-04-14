<?php
/**
 * Created by PhpStorm.
 * User: Peter Semenyuk
 * Date: 014, 14, 04, 2016
 * Time: 11:40
 */

namespace Aot\Sviaz\Rule\Container\Section;


abstract class Base
{
    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    abstract public function getRules();
}