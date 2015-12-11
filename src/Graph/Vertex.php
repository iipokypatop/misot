<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:10
 */

namespace Aot\Graph;


abstract class Vertex extends \Fhaculty\Graph\Vertex
{
    protected static $id = 0;
    /**
     * @var \Aot\Graph\Vertex
     */
    protected $previous;

    public static function getNextId()
    {
        return ++static::$id;
    }

    public function wrap(\Aot\Graph\Vertex $previous)
    {
        $this->previous = $previous;
    }

    /**
     * @param int $steps_back
     * @return Vertex
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
     * @return null
     */
    public function getWordForm()
    {
        if ($this->getPrevious() === null) {
            return null;
        }
        return $this->getPrevious()->getWordForm();
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