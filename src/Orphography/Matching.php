<?php

namespace Aot\Orphography;


class Matching
{
    /**
     * @var \Aot\Orphography\Dictionary\Base
     */
    protected $dictionary;
    /**
     * @var int
     */
    protected $state;

    /**
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     * @param int $state
     */
    protected function __construct(\Aot\Orphography\Dictionary\Base $dictionary, $state)
    {
        $this->dictionary = $dictionary;
        $this->state = $state;
    }

    /**
     * @param \Aot\Orphography\Dictionary\Base $dictionary
     * @param bool $state
     * @return static
     */
    public static function create(\Aot\Orphography\Dictionary\Base $dictionary, $state)
    {
        return new static($dictionary, $state);
    }
}