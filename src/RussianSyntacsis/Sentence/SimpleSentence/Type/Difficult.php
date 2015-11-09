<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:32
 */

namespace Aot\RussianSyntacsis\Sentence\SimpleSentence\Type;


class Difficult extends Base
{
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence[] */
    protected $depended_simple_sentences = [];
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence[] */
    protected $main_simple_sentences = [];

    /**
     * @return Difficult
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence[]
     */
    public function getDependedSimpleSentences()
    {
        return $this->depended_simple_sentences;
    }

    /**
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended_simple_sentence
     */
    public function addDependedSimpleSentence(
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $depended_simple_sentence
    ) {
        $this->depended_simple_sentences[] = $depended_simple_sentence;
    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence[]
     */
    public function getMainSimpleSentences()
    {
        return $this->main_simple_sentences;
    }

    /**
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main_simple_sentence
     */
    public function setMainSimpleSentence(
        \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $main_simple_sentence
    ) {
        $this->main_simple_sentences[] = $main_simple_sentence;
    }


}