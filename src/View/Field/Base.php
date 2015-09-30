<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 12:08
 */

namespace Aot\View\Field;


class Base
{
    protected $storage = [];

    /**
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    public static function create()
    {
        return new static();
    }

    protected $callable;

    public function setView(callable $callable)
    {
        $this->callable = $callable;
    }


    public function draw()
    {
        $callable = $this->callable;

        return $callable($this);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function charge($key, $value)
    {
        assert(is_string($key));

        $this->storage[$key] = $value;
    }
}