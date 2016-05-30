<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:29
 */

namespace Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

abstract class Base
{
    /** @var boolean[][] Поле для оптимизации, карта сранений морфологии */
    protected static $map_of_comparisons_morphology = [];

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
    abstract public function match(
        MorphologyBase $left,
        MorphologyBase $right
    );

    public static function create()
    {
        if (static::$map_of_comparisons_morphology === []) {
            static::$map_of_comparisons_morphology = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getMapOfComparisonsMorphology();
        }
        return new static;
    }

}