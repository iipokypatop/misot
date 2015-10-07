<?php

namespace Aot\Orphography;


class Matching
{
    /**
     * @var \Aot\Orphography\Language\Base
     */
    protected $dictionary;
    /**
     * @var int
     */
    protected $state;

    /**
     * @param \Aot\Orphography\Language\Base $dictionary
     * @param int $state
     */
    protected function __construct(\Aot\Orphography\Language\Base $dictionary, $state)
    {
        $this->dictionary = $dictionary;
        $this->state = $state;
    }

    /**
     * @param \Aot\Orphography\Language\Base $dictionary
     * @param bool $state
     * @return static
     */
    public static function create(\Aot\Orphography\Language\Base $dictionary, $state)
    {
        return new static($dictionary, $state);
    }
}