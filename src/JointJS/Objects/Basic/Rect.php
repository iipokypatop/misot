<?php

namespace Aot\JointJS\Objects\Basic;


class Rect extends \Aot\JointJS\JSON
{
    protected function getFieldForJsonSerialize()
    {
        return [
            'id',
            'type',
            'attrs',
            'position',
            'angle',
            'size',
            'z',
            'embeds',
            'parent',
            'magnet',

        ];
    }

    /**
     * State constructor.
     */
    public function __construct()
    {
        $this->setType('basic.Rect');
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\JointJS\Objects\Position $position
     * @return $this
     */
    public function setPosition(\Aot\JointJS\Objects\Position $position)
    {
        $this->set('position', $position);
        return $this;
    }


    /**
     * @return \Aot\JointJS\Objects\Position | null
     */
    public function getPosition()
    {
        return
            $this->isSetUp('position')
                ? $this->get('position')
                : null;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        assert(is_string($id));

        $this->set('id', $id);

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    protected function setType($type)
    {
        assert(is_string($type));
        $this->set('type', $type);
        return $this;
    }

    /**
     * @param \Aot\JointJS\Objects\Attr $attrs
     * @return $this
     */
    public function setAttrs(\Aot\JointJS\Objects\Attr $attrs)
    {
        $this->set('attrs', $attrs);
        return $this;
    }

    /**
     * @param int $angle
     * @return $this
     */
    public function setAngle($angle)
    {
        assert(is_int($angle));
        $this->set('angle', $angle);
        return $this;
    }

    /**
     * @param \Aot\JointJS\Objects\Size $size
     * @return $this
     */
    public function setSize(\Aot\JointJS\Objects\Size $size)
    {
        $this->set('size', $size);
        return $this;
    }

    /**
     * @param int $z
     * @return $this
     */
    public function setZ($z)
    {
        assert(is_int($z));
        $this->set('z', $z);
        return $this;
    }

    /**
     * @param \int[] $embeds
     * @return $this
     */
    public function setEmbeds(array $embeds)
    {
        assert(is_array($embeds));

        foreach ($embeds as $value) {
            assert(is_int($value));
        }

        $this->set('embeds', $embeds);
        return $this;
    }

    /**
     * @param string $parent
     * @return $this
     */
    public function setParent($parent)
    {
        assert(is_string($parent));
        $this->set('parent', $parent);
        return $this;
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


