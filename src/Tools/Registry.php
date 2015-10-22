<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22.10.2015
 * Time: 11:30
 */

namespace Aot\Tools;


class Registry implements \Iterator, \Countable
{
    /** @var int */
    protected $position = 0;
    /** @var string[] */
    protected $texts = [];
    /** @var \Aot\RussianMorphology\Slovo[][] */
    protected $slova = [];

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param string $text
     * @param \Aot\RussianMorphology\Slovo[][] $array_slova
     */
    public function add($text, $array_slova)
    {
        assert(is_string($text));
        foreach ($array_slova as $slova) {
            foreach ($slova as $slovo) {
                assert(is_a($slovo, \Aot\RussianMorphology\Slovo::class, true));
            }
        }
        $this->texts [] = $text;
        $this->slova [] = current($array_slova);
    }


    /**
     * @return \Aot\RussianMorphology\Slovo[]
     */
    public function current()
    {
        return $this->slova[$this->position];
    }


    public function next()
    {
        $this->position++;
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->texts[$this->position];
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return array_key_exists($this->position, $this->texts);
    }


    public function rewind()
    {
        $this->position = 0;
    }


    /**
     * @return int
     */
    public function count()
    {
        return count($this->slova);
    }
}