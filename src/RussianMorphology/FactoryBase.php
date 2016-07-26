<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 30/11/15
 * Time: 19:48
 */

namespace Aot\RussianMorphology;

abstract class FactoryBase
{
    protected static $uniqueInstances = null;

    /**
     * FactoryBase constructor.
     */
    protected function __construct()
    {

    }

    /**
     * @return static
     */
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\Slovo
     */
    abstract public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw);
}