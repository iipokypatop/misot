<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 006, 06, 03, 2016
 * Time: 15:24
 */

namespace Aot\RussianMorphology\Factory2;


class Cache
{

    /**
     * @var \Aot\RussianMorphology\Slovo[][]
     */
    public $__cache_slova = [];


    
    protected function __construct()
    {

    }

    /**
     * @return static
     */
    public static function create()
    {
        $ob = new static;

        return $ob;
    }

}