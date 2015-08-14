<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 16:43
 */

namespace Aot\JointJS\Objects;


class Circle extends \Aot\JointJS\JSON
{
    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'stroke-width'
        ];
    }

    /**
     * @param int $stroke_width
     * @return $this
     */
    public function setStrokeWidth($stroke_width)
    {
        assert(is_int($stroke_width));
        $this->set('stroke-width', $stroke_width);
        return $this;
    }

    public static function create()
    {
        return new static();
    }

}
