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
    /** @var string */
    protected $asserted_left_class;


    /** @var  MorphologyMatchingOperator\Base */
    protected $operator;


    /** @var  string */
    protected $asserted_right_class;

    /**
     * @param string $asserted_left_class
     * @param MorphologyMatchingOperator\Base $operator
     * @param string $asserted_right_class
     */
    protected function __construct($asserted_left_class, MorphologyMatchingOperator\Base $operator, $asserted_right_class)
    {
        if (!is_string($asserted_left_class)) {
            throw new \RuntimeException("incorrect argument type");
        }
        if (!is_string($asserted_right_class)) {
            throw new \RuntimeException("incorrect argument type");
        }
        if (!class_exists($asserted_right_class)) {
            throw new \RuntimeException("class $asserted_right_class not found");
        }
        if (!class_exists($asserted_left_class)) {
            throw new \RuntimeException("class $asserted_left_class not found");
        }
        if (!is_a($asserted_left_class, MorphologyBase::class, true)) {
            throw new \RuntimeException("class $asserted_left_class not instanceof " . MorphologyBase::class);
        }
        if (!is_a($asserted_right_class, MorphologyBase::class, true)) {
            throw new \RuntimeException("class $asserted_right_class not instanceof " . MorphologyBase::class);
        }

        $this->asserted_left_class = $asserted_left_class;
        $this->operator = $operator;
        $this->asserted_right_class = $asserted_right_class;
    }


    /**
     * @param string $asserted_left_class
     * @param MorphologyMatchingOperator\Base $operator
     * @param string $asserted_right_class
     * @return static
     */
    public static function create($asserted_left_class, MorphologyMatchingOperator\Base $operator, $asserted_right_class)
    {
        return new static($asserted_left_class, $operator, $asserted_right_class);
    }

    protected $message;

    /**
     * @param MorphologyBase|\Aot\Sviaz\SequenceMember\Base $actual_left
     * @param MorphologyBase|\Aot\Sviaz\SequenceMember\Base $actual_right
     * @return bool
     */
    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual_left, \Aot\Sviaz\SequenceMember\Base $actual_right)
    {
        if ($actual_left === $actual_right) {
            throw new \RuntimeException("wtf?!");
        }

        if (!$actual_left instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            $this->message = "левый операнд должен быть наследником класса " . \Aot\Sviaz\SequenceMember\Word\Base::class;
            return false;
        }

        if (!$actual_right instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            $this->message = "правый операнд должен быть наследником класса " . \Aot\Sviaz\SequenceMember\Word\Base::class;
            return false;
        }

        $morphology_left = $actual_left->getSlovo()->getMorphologyByClass_TEMPORARY(
            $this->asserted_left_class
        );
        if (null === $morphology_left) {
            $this->message = "морфологичесекий признак левого операнда не соответствует ожидаемому";
            return false;
        }

        $morphology_right = $actual_right->getSlovo()->getMorphologyByClass_TEMPORARY(
            $this->asserted_right_class
        );
        if (null === $morphology_right) {
            $this->message = "морфологичесекий признак правого операнда не соответствует ожидаемому";
            return false;
        }

        return $this->operator->match($morphology_left, $morphology_right);
    }


}