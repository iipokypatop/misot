<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:45
 */

namespace Aot\Text\TextParser\Replacement;


use Aot\Text\TextParser\PatternsRegistry;

abstract class Replace
{
    protected $registry = [];
    protected $index = 0;


    protected $patterns = [];


    /**
     * @return object Aot\Text\TextParser\Replacement\Replace
     */
    static public function create()
    {
        return new static();
    }

    public function __construct()
    {
        $this->patterns = PatternsRegistry::getPatterns(static::class);
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
        $this->registry[++$this->index] = $record;

        return "{%" . $this->index . "%}";
    }


    /**
     * @return []
     */
    abstract protected function getPatterns();
}

