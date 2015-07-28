<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:45
 */

namespace Aot\Text\TextParser\Replacement;


abstract class Base
{
    protected $registry = [];
    protected $index = 0;

    protected $patterns = [];


    /**
     * @param $registry
     * @return object Aot\Text\TextParser\Replacement\Replace
     */
    static public function create($registry)
    {
        return new static($registry);
    }

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $text
     * @return string
     */
    public function replace($text)
    {
        $text = preg_replace_callback(
            $this->getPatterns(),
            [$this, 'putInRegistry'],
            $text
        );

        return $text;
    }

    /**
     * @param $record
     * @return null
     */
    protected function putInRegistry($record)
    {
//        $this->registry[++$this->index] = $record;

        return "{%" . 111 . "%}";
    }


    /**
     * @return []
     */
    abstract protected function getPatterns();
}

