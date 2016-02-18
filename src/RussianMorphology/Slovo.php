<?php

namespace Aot\RussianMorphology;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:05
 */
abstract class Slovo implements \Aot\Unit
{
    /**
     * read only !
     * @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[]
     */
    public $storage = [];
    /**
     * read only !
     * @var string
     */
    public $text;
    /**
     * read only !
     * @var string
     */
    public $initial_form;
    /**
     * @var string[]
     */
    protected $__cache_morphology = [];


    /**
     * @param string $text
     */
    protected function __construct($text)
    {
        assert(!empty($text));

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getInitialForm()
    {
        return $this->initial_form;
    }

    /**
     * @param string $initial_form
     */
    public function setInitialForm($initial_form)
    {
        assert(is_string($initial_form));
        assert('' !== ($initial_form));
        $this->initial_form = $initial_form;
    }

    public function __get($name)
    {
        if (array_key_exists($name, static::getMorphology())) {
            return $this->storage[$name];
        }

        throw new \RuntimeException("unsupported field name exception");
    }

    public function __set($name, $value)
    {
        if (empty($this->__cache_morphology[static::class])) {
            $this->__cache_morphology[static::class] = static::getMorphology();
        }

        if (!array_key_exists($name, $this->__cache_morphology[static::class])) {
            throw new \RuntimeException("unsupported field exception " . var_export($name, true));
        }

        if (!is_subclass_of($value, $this->__cache_morphology[static::class][$name])) {
            throw new \RuntimeException("incorrect field type");
        }

        $this->storage[$name] = $value;
    }

    /**
     * return string[]
     */
    public static function getMorphology()
    {
        return [];
    }

    /**
     * @param $classname
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base |  null
     */
    public function getMorphologyByClass_TEMPORARY($classname)
    {
        $values = [];
        foreach ($this->storage as $name => $value) {
            if ($value instanceof $classname) {
                $values[] = $value;
            }
        }

        /// TEMPORARY start
        foreach ($this as $name => $value) {

            if ($value instanceof $classname) {
                $values[] = $value;
            }
        }
        /// TEMPORARY end

        if (count($values) > 1) {
            throw new \RuntimeException("more than one subclass of $classname");
        }

        if (count($values) === 1) {
            return $values[0];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return \Aot\RussianMorphology\ChastiRechi\MorphologyBase[]
     */
    public function getMorphologyStorage()
    {
        return $this->storage;
    }
}