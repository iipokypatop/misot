<?php

namespace Aot\RussianMorphology\ChastiRechi\Other;

use Aot\RussianMorphology\Slovo;

class Base extends Slovo
{
    /**
     * @param string $text
     * @return Base
     */
    public static function create($text)
    {
        return new static($text);
    }

    /**
     * Base constructor.
     * @param string $text
     */
    protected function __construct($text)
    {
        parent::__construct($text);
    }
}