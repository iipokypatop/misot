<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:15
 */

namespace Aot\RussianSyntacsis\Sentence;

class Sentence
{
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence[] */
    protected $simple_sentences = [];
    /** @var \Aot\RussianSyntacsis\Sentence\Member\Base[] */
    protected $members = [];
    /** @var \Aot\RussianSyntacsis\Sentence\Member\Relation[] */
    protected $relations = [];

    /**
     * @return Sentence
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
    public function getSimpleSentences()
    {
        return $this->simple_sentences;
    }

    /**
     * @param \Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $simple_sentence
     */
    public function addSimpleSentence(\Aot\RussianSyntacsis\Sentence\SimpleSentence\SimpleSentence $simple_sentence)
    {
        $this->simple_sentences[] = $simple_sentence;
    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\Member\Base[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param \Aot\RussianSyntacsis\Sentence\Member\Base $member
     */
    public function addMember(\Aot\RussianSyntacsis\Sentence\Member\Base $member)
    {
        $this->members[] = $member;
    }

    /**
     * @return \Aot\RussianSyntacsis\Sentence\Member\Relation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param \Aot\RussianSyntacsis\Sentence\Member\Relation $relation
     */
    public function setRelation(\Aot\RussianSyntacsis\Sentence\Member\Relation $relation)
    {
        $this->relations[] = $relation;
    }


}