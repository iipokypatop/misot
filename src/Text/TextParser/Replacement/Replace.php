<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:45
 */

namespace Aot\Text\TextParser\Replacement;


use Aot\Text\TextParser\PatternsRegistry;

Class Replace
{
    protected $registry = [];
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
        $this->patterns = PatternsRegistry::getPatterns(static::class);
    }

    /**
     * @param $text
     * @return string
     */
     public function replace($text){
        $text = preg_replace_callback(
            $this->patterns,
            [$this, 'putInRegistry'],
            $text
        );

        return $text;
    }

    /**
     * @param $match
     * @return null
     */
    protected function putInRegistry($match)
    {
        $this->registry[] = $match[0];
        $index = count($this->registry) - 1;
        return "{{" . $index . "}}";
    }

    /**
     * @return array
     */
    public function getRegistry()
    {
        return $this->registry;
    }
}