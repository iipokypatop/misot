<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:29
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

abstract class Base
{
    /**
     * Base constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @param MorphologyBase $left
     * @param MorphologyBase $right
     * @return boolean
     */
    abstract public function match(MorphologyBase $left, MorphologyBase $right);

    public static function create()
    {
        return new static;
    }

}