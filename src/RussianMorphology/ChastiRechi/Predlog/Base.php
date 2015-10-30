<?php

namespace Aot\RussianMorphology\ChastiRechi\Predlog;

use Aot\RussianMorphology\Slovo;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:14
 */
class Base extends Slovo
{
    public static function create($text)
    {
        return new static($text);
    }

}