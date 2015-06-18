<?php

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:05
 */
class Slovo
{
    protected $text;

    /**
     * Word constructor.
     * @param $text
     */
    public function __construct($text)
    {
        assert(!empty($text));
        $this->text = $text;
    }
}