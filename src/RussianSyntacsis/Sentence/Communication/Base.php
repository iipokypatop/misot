<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.11.2015
 * Time: 18:21
 */
namespace Aot\RussianSyntacsis\Sentence\Communication;

class Base
{
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence */
    protected $main;
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence */
    protected $depended;

    /**
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended
     * @return Base
     */
    public static function create(
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main,
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended
    ) {
        $obj = new static($main, $depended);
        return $obj;
    }

    protected function __construct(
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main,
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended
    ) {
        $this->main = $main;
        $this->depended = $depended;
    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence
     */
    public function getDepended()
    {
        return $this->depended;
    }
}