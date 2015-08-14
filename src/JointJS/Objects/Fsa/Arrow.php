<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 11.08.2015
 * Time: 14:36
 */

namespace Aot\JointJS\Objects\Fsa;


use Aot\JointJS\Objects\Attr;

class Arrow extends \Aot\JointJS\JSON
{

    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'id',
            'type',
            'attrs',
            'vertices',
            'source',
            'target',
            'labels',
            'z',
        ];
    }

    /**
     * State constructor.
     */
    public function __construct()
    {
        $this->setType('fsa.Arrow');
    }

    public static function create()
    {
        return new static();
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
     * @param Attr $attrs
     * @return $this
     */
    public function setAttrs(Attr $attrs)
    {
        $this->set('attrs', $attrs);
        return $this;
    }

    /**
     * @param \Aot\JointJS\Objects\Point[] $vertices
     * @return $this
     */
    public function setVertices(array $vertices = [])
    {
        foreach ($vertices as $vertice) {
            assert($vertice instanceof \Aot\JointJS\Objects\Point);
        }

        $this->set('vertices', $vertices);


        return $this;
    }

    /**
     * @param \Aot\JointJS\Objects\Id $id
     * @return $this
     */
    public function setSource(\Aot\JointJS\Objects\Id $id)
    {
        $this->set('source', $id);
        return $this;
    }

    /**
     * @param \Aot\JointJS\Objects\Id $id
     * @return $this
     */
    public function setTarget(\Aot\JointJS\Objects\Id $id)
    {
        $this->set('target', $id);
        return $this;
    }

    public function setLabels()
    {
        throw new \RuntimeException("not implemented yet");
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
}

