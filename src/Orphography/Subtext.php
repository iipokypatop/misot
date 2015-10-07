<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.10.2015
 * Time: 14:16
 */
namespace Aot\Orphography;

class Subtext
{
    /* @var string $text */
    protected $text;
    /* @var \Aot\Orphography\Suggestion[] $suggestions */
    protected $suggestions = [];
    /* @var \Aot\Orphography\Matching[] $matchings */
    protected $matchings = [];


    /**
     * @param string $text
     */
    protected function __construct($text)
    {
        $this->setText($text);
    }

    /**
     * @param string $text
     * @return static
     */
    public static function create($text)
    {
        assert(is_string($text));
        return new static($text);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param Suggestion $suggestion
     */
    public function addSuggestion(\Aot\Orphography\Suggestion $suggestion)
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param Matching $matching
     */
    public function addMatching(\Aot\Orphography\Matching $matching)
    {
        $this->matchings[] = $matching;
    }

}