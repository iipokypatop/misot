<?php


namespace Aot\RussianMorphology\ChastiRechi\Soyuz;

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

    protected function __construct($text)
    {
        parent::__construct($text);
    }
}