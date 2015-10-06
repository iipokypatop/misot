<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.10.2015
 * Time: 14:16
 */
namespace Aot\Orphography;

class Word
{
    /* @var string $text */
    protected $text;
    /* @var \Aot\Orphography\Suggestion[] $suggestions */
    protected $suggestions = [];
    /* @var \Aot\Orphography\Matching[] $matchings */
    protected $matchings = [];


    protected function __construct($text)
    {
        $this->setText($text);
    }

    public static function create($text)
    {
        return new static($text);
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

}