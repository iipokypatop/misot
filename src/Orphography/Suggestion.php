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
    /** @var \Aot\Orphography\Word[] $words */
    protected $words = [];

    /** @var \Aot\Orphography\Dictionary\Base $dictionary */
    protected $dictionary;

    /** @var int[] $weights */
    protected $weights = [];

    /**
     * @param \Aot\Orphography\Word[] $words
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     */
    protected function __construct(array $words, array $weights, $dictionary)
    {
        foreach ($words as $word) {
            assert(is_a($word, \Aot\Orphography\Word::class));
        }

        foreach ($weights as $weight) {
            assert(is_int($weight));
        }

        $this->words = $words;
        $this->weights = $weights;
        $this->dictionary = $dictionary;
    }

    /**
     * @param \Aot\Orphography\Word[] $words
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     * @return static
     */
    public static function create(array $words, $weights, $dictionary)
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
     * @return mixed
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * @return array
     */
    public function getWeights()
    {
        return $this->weights;
    }
}

