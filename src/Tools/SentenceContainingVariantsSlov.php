<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22.10.2015
 * Time: 11:30
 */

namespace Aot\Tools;


class SentenceContainingVariantsSlov implements \Iterator, \Countable
{
    /** @var int */
    protected $position = 0;
    /** @var string[] */
    protected $texts = [];
    /** @var \Aot\RussianMorphology\Slovo[][] */
    protected $slova = [];

    /**
     * @return SentenceContainingVariantsSlov
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param string $text
     * @param \Aot\RussianMorphology\Slovo[][] $slova
     */
    public function add($text, $slova)
    {
        assert(is_string($text));
        assert(count($slova) === 1);
        foreach ($slova[0] as $slovo) {
            assert(is_a($slovo, \Aot\RussianMorphology\Slovo::class, true));
        }
        $this->texts [] = $text;
        $this->slova [] = $slova[0];
    }

    /**
     * @return \Aot\RussianMorphology\Slovo[]
     */
    public function current()
    {
        return $this->slova[$this->position];
    }

    /**
     * return void
     */
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

    /**
     * return void
     */
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