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
    /** @var \Aot\RussianSyntacsis\Sentence\Communication\Base[] */
    protected $communications = [];

    /** @var  string */
    protected $text;

    /**
     * @return Sentence
     */
    public static function create($text)
    {
        $obj = new static($text);
        return $obj;
    }

    protected function __construct($text)
    {
        assert(is_string($text));
        $this->text = $text;
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
    public function addRelation(\Aot\RussianSyntacsis\Sentence\Member\Relation $relation)
    {
        $this->relations[] = $relation;
    }

    /**
     * @return Communication\Base[]
     */
    public function getCommunications()
    {
        return $this->communications;
    }

    /**
     * @param Communication\Base $communication
     */
    public function addCommunication(\Aot\RussianSyntacsis\Sentence\Communication\Base $communication)
    {
        $this->communications = $communication;
    }


}