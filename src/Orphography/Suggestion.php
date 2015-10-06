<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.10.2015
 * Time: 14:58
 */

namespace Aot\Orphography;


class Suggestion
{
    /* $var \Aot\Orphography\Word[] $words */
    protected $words = [];
    /* $var \Aot\Orphography\Dictionary $dictionary */
    protected $dictionary;
    /* $var int[] $weights */
    protected $weights = [];

    /**
     * @param \Aot\Orphography\Word[] $words
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary $dictionary
     */
    protected function __construct($words, $weights, $dictionary)
    {
        $this->setWords($words);
        $this->setWeights($weights);
        $this->setDictionary($dictionary);
    }

    /**
     * @param \Aot\Orphography\Word[] $words
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary $dictionary
     * @return static
     */
    public static function create($words, $weights, $dictionary)
    {
        return new static($words, $weights, $dictionary);
    }

    /**
     * @return array
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param array $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }

    /**
     * @return mixed
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * @param mixed $dictionary
     */
    public function setDictionary($dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @return array
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @param array $weights
     */
    public function setWeights($weights)
    {
        $this->weights = $weights;
    }
}