<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:38
 */

namespace Aot\Text\TextParser\Filters;


abstract class Filter
{

    static public function create(){
        return new static();
    }

    /**
     * @param $text
     * @return string
     */
    abstract public function filter($text);
}