<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 14:28
 */

namespace Aot\JointJS\Objects;


class Point extends \Aot\JointJS\JSON
{
    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'x',
            'y',
        ];
    }

    /**
     * Position constructor.
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y)
    {
        $this->setX($x);
        $this->setY($y);
    }

    /**
     * @param int $x
     * @param int $y
     * @return static
     */
    public static function create($x, $y)
    {
        return new static($x, $y);
    }

    /**
     * @param int $x
     * @return $this
     */
    public function setX($x)
    {
        assert(is_int($x));
        $this->set('x', $x);
        return $this;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function setY($y)
    {
        assert(is_int($y));
        $this->set('y', $y);
        return $this;
    }


}