<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:21
 */

namespace Aot\Graph;


abstract class Edge extends \Fhaculty\Graph\Edge\Directed implements IEdge
{
    /**
     * @var \Aot\Graph\IEdge
     */
    protected $previous;

    /**
     * @param \Aot\Graph\IEdge $previous
     */
    public function wrap(\Aot\Graph\IEdge $previous)
    {
        $this->previous = $previous;
    }

    /**
     * @param int $steps_back
     * @return \Aot\Graph\Edge
     */
    public function getPrevious($steps_back = 1)
    {
        assert(is_int($steps_back));
        if ($steps_back < 0) {
            throw new \RuntimeException('Invalid steps_back value');
        }
        if ($steps_back === 0) {
            return $this;
        }

        if ($steps_back === 1) {
            return $this->previous;
        }

        if ($steps_back > 1) {
            return $this->previous->getPrevious($steps_back - 1);
        }
    }

    /**
     *
     */
    public function getWrappedClasses()
    {

        if ($this->getPrevious() === null) {
            return [static::class];
        }
        $res = $this->getPrevious()->getWrappedClasses();
        array_unshift($res, static::class);
        return $res;

    }


}