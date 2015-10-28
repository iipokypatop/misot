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
    const REGULAR_FOR_WHITE_LIST = '/[А-Яю-яёЁ\-]+/';

    /** @var int */
    protected $position = 0;
    /** @var string[] */
    protected $texts = [];
    /** @var \Aot\RussianMorphology\Slovo[][] */
    protected $slova = [];
    /**
     * @var string
     */
    protected $raw_sentence_text;

    /** @var SentenceContainingVariantsSlov */
    protected $previous;

    /**
     * @param string $raw_sentence_text
     * @return SentenceContainingVariantsSlov
     */
    public static function create($raw_sentence_text)
    {
        return new static($raw_sentence_text);
    }

    /**
     * @param string $raw_sentence_text
     */
    protected function __construct($raw_sentence_text)
    {
        assert(is_string($raw_sentence_text));
        assert(!empty($raw_sentence_text));

        $this->raw_sentence_text = $raw_sentence_text;
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

    /**
     * @return string
     */
    public function getRawSentenceText()
    {
        return $this->raw_sentence_text;
    }

    /**
     * @return SentenceContainingVariantsSlov
     */
    public function getSentenceWithoutPunctuation()
    {
        $obj = new static($this->raw_sentence_text);
        foreach ($this->texts as $key => $text) {
            if (!preg_match(static::REGULAR_FOR_WHITE_LIST, $text)) {
                continue;
            }
            $obj->add($this->texts[$key], [$this->slova[$key]]);
        }
        $obj->previous = $this;
        return $obj;
    }

    /**
     * @return SentenceContainingVariantsSlov
     */
    public function getPrevious()
    {
        return $this->previous;
    }
}