<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 14:56
 */

namespace Aot\JointJS\Objects;


class Size extends \Aot\JointJS\JSON
{

    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'width',
            'height',
        ];
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return static
     */
    public static function create($width, $height)
    {
        return new static($width, $height);
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        assert(is_int($width));
        $this->set('width', $width);
        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        assert(is_int($height));
        $this->set('height', $height);
        return $this;
    }

}