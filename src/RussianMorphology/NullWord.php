<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 019, 19, 02, 2016
 * Time: 3:16
 */

namespace Aot\RussianMorphology;


class NullWord extends Slovo
{
    public static function create($text)
    {
        $ob = new static($text);

        $ob->initial_form = $text;

        return $ob;
    }
}