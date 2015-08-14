<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 16:40
 */

namespace Aot\JointJS\Objects;


class Text extends \Aot\JointJS\JSON
{
    /**
     * Text constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'text',
            'font-weight',
            'magnet'
        ];
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        assert(is_string($text));
        $this->set('text', $text);
        return $this;
    }

    /**
     * @param int $font_weight
     * @return $this
     */
    public function setFontWeight($font_weight)
    {
        assert(is_int($font_weight));
        $this->set('font-weight', $font_weight);
        return $this;
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param bool $magnet
     * @return $this
     */
    public function setMagnet($magnet)
    {
        assert(is_bool($magnet));
        $this->set('magnet', $magnet);
        return $this;
    }


}