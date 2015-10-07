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
    /** @var \Aot\Orphography\Subtext[] $subtexts */
    protected $subtexts = [];

    /** @var \Aot\Orphography\Dictionary\Base $dictionary */
    protected $dictionary;

    /** @var int[] $weights */
    protected $weights = [];

    /**
     * @param \Aot\Orphography\Subtext[] $subtexts
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     */
    protected function __construct(array $subtexts, array $weights, \Aot\Orphography\Dictionary\Base $dictionary)
    {
        foreach ($subtexts as $subtext) {
            assert(is_a($subtext, \Aot\Orphography\Subtext::class));
        }

        foreach ($weights as $weight) {
            assert(is_int($weight));
        }

        $this->subtexts = $subtexts;
        $this->weights = $weights;
        $this->dictionary = $dictionary;
    }

    /**
     * @param \Aot\Orphography\Subtext[] $subtexts
     * @param int[] $weights
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     * @return static
     */
    public static function create(array $subtexts, $weights, \Aot\Orphography\Dictionary\Base $dictionary)
    {
        return new static($subtexts, $weights, $dictionary);
    }

    /**
     * @return \Aot\Orphography\Subtext[]
     */
    public function getSubtexts()
    {
        return $this->subtexts;
    }

    /**
     * @return \Aot\Orphography\Dictionary\Base
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }

    /**
     * @return int[]
     */
    public function getWeights()
    {
        return $this->weights;
    }
}

