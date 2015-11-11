<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:24
 */

namespace Aot\RussianSyntacsis\Sentence\Member;

abstract class Base
{
    /** @var  string */
    protected $text;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return \Aot\RussianSyntacsis\Sentence\Member\Base
     */
    public static function create($text)
    {
        assert(is_string($text));
        $obj = new static();
        $obj->text = $text;
        return $obj;
    }


    protected function __construct()
    {

    }
}