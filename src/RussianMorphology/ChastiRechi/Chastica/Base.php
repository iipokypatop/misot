<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 0:13
 */

namespace Aot\RussianMorphology\ChastiRechi\Chastica;


use Aot\RussianMorphology\Slovo;

class Base extends Slovo
{
    /**
     * @param string $text
     */
    protected function __construct($text)
    {
        parent::__construct($text); // TODO: Change the autogenerated stub
    }

    public static function create($text)
    {
        return new static($text);
    }


}