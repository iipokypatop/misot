<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.11.2015
 * Time: 18:21
 */
namespace Aot\RussianSyntacsis\Sentence\Communication;

class Communication
{
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence */
    protected $main;
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence */
    protected $depended;

    /**
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended
     * @return Communication
     */
    public static function create(
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main,
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended
    ) {
        $obj = new static();
        $obj->main = $main;
        $obj->depended = $depended;
        return $obj;
    }

    protected function __construct()
    {

    }
}