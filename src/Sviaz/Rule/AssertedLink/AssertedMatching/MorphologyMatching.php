<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:28
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching;


use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class MorphologyMatching extends Base
{
    /** @var  MorphologyBase */
    protected $asserted_left;


    /** @var  MorphologyMatchingOperator\Base */
    protected $operator;


    /** @var  MorphologyBase */
    protected $asserted_right;

    /**
     * MorphologyMatching constructor.
     * @param MorphologyBase $asserted_left
     * @param MorphologyMatchingOperator\Base $operator
     * @param MorphologyBase $asserted_right
     */
    protected function __construct(MorphologyBase $asserted_left, MorphologyMatchingOperator\Base $operator, MorphologyBase $asserted_right)
    {
        $this->asserted_left = $asserted_left;
        $this->operator = $operator;
        $this->asserted_right = $asserted_right;
    }

    public function getLeftOperandType()
    {
        return get_class($this->asserted_left);
    }

    public function getRightOperandType()
    {
        return get_class($this->asserted_right);
    }

    public static function create(MorphologyBase $asserted_left, MorphologyMatchingOperator\Base $operator, MorphologyBase $asserted_right)
    {
        return new static($asserted_left, $operator, $asserted_right);
    }

    /**
     * @param \Aot\RussianMorphology\ChastiRechi\MorphologyBase $actual_left
     * @param \Aot\RussianMorphology\ChastiRechi\MorphologyBase $actual_rigth
     * @return bool
     */
    public function match($actual_left, $actual_rigth)
    {
        return false;
    }
}