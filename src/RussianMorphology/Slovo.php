<?php

namespace Aot\RussianMorphology;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:05
 */
abstract class Slovo
{
    protected static $children = [
        ChastiRechi\Suschestvitelnoe\Base::class,
        ChastiRechi\Prilagatelnoe\Base::class
    ];

    protected $storage = [];

    protected function init()
    {
        foreach ($this->getMorphology() as $name => $class) {
            $this->storage[$name] = null;
        }
    }

    /**
     * @return \Aot\RussianMorphology\ChastiRechi\MorphologyBase[]
     */
    public function getMorphology()
    {
        return [];
    }

    function __get($name)
    {
        if (array_key_exists($name, static::getMorphology())) {
            return $this->storage[$name];
        }

        throw new \RuntimeException("unsupported field name exception");
    }

    function __set($name, $value)
    {
        if (!array_key_exists($name, static::getMorphology())) {
            throw new \RuntimeException("unsupported field exception");
        }

        if (!is_subclass_of($value, static::getMorphology()[$name])) {
            throw new \RuntimeException("incorrect field type");
        }

        $this->storage[$name] = $value;
    }


    protected $text;

    /**
     * @param string $text
     */
    protected function __construct($text)
    {
        assert(!empty($text));

        $this->text = $text;

        $this->init();
    }

    /**
     * @param $classname
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base |  null
     */
    public function getMorphologyByClass_TEMPORARY($classname)
    {
        #throw new \RuntimeException("more than one subclass of $classname");

        $values = [];
        foreach ($this->storage as $name => $value) {
            // if ($value instanceof $classname) {
            if (is_subclass_of($value, $classname)) {
                $values[] = $value;
            }
        }

        if (count($values) > 1) {
            throw new \RuntimeException("more than one subclass of $classname");
        }

        if (count($values) === 1) {
            return $values[0];
        }

        return null;
    }
}

