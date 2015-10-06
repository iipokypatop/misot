<?php

namespace Aot\Orphography;


class Matching
{
    protected $dictionary;
    protected $state;

    /**
     * @param Dictionary\Driver\Pspell\Dictionary $dictionary
     * @param int $state
     */
    protected function __construct(\Aot\Orphography\Dictionary\Driver\Pspell\Dictionary $dictionary, $state)
    {
        $this->dictionary = $dictionary;
        $this->state = $state;
    }

    /**
     * @param \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary $dictionary
     * @param bool $state
     * @return static
     */
    public static function create($dictionary, $state)
    {
        return new static($dictionary, $state);
    }
}