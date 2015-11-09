<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:25
 */

namespace Aot\RussianSyntacsis\Sentence\Member;


class Punctuation extends Base
{
    /**
     * @return Punctuation
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }
}