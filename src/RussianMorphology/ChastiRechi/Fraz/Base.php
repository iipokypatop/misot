<?php

namespace Aot\RussianMorphology\ChastiRechi\Fraz;

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
}