<?php

namespace Aot\Sviaz\Processors\AotGraph\Filters;

/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 26/07/16
 * Time: 19:05
 */
abstract class Base
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    abstract public function run(\Aot\Graph\Slovo\Graph $graph);
}