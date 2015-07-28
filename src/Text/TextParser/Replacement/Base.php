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
    protected $registry;


    /**
     * @param $registry
     * @return object Aot\Text\TextParser\Replacement\Base
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
        $index = $this->registry->add($record[0]);
        return "{%" . $index . "%}";
    }


    /**
     * @return []
     */
    abstract protected function getPatterns();
}

