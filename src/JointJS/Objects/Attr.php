<?php

namespace Aot\JointJS\Objects;

class Attr extends \Aot\JointJS\JSON
{
    protected function getFieldForJsonSerialize()
    {
        return [
            'text',
            'circle',
            '.',
        ];
    }

    /**
     * @param Text $text
     * @return $this
     */
    public function setText(\Aot\JointJS\Objects\Text $text)
    {
        $this->set('text', $text);
        return $this;
    }


    /**
     * @param Circle $circle
     * @return $this
     */
    public function setCircle(\Aot\JointJS\Objects\Circle $circle)
    {
        $this->set('circle', $circle);
        return $this;
    }


    public static function create()
    {
        return new static();
    }

    public function setDot(\Aot\JointJS\Objects\Text $text)
    {
        $this->set('.', $text);

        return $this;
    }
}