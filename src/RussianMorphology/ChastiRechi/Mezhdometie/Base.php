<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\RussianMorphology\ChastiRechi\Mezhdometie;

use Aot\RussianMorphology\Slovo;


class Base extends Slovo
{
    public static function create($text)
    {
        return new static($text);
    }

    protected function __construct($text)
    {
        parent::__construct($text);
    }
}