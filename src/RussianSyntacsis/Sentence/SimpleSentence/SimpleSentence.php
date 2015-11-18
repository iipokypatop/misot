<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:18
 */

namespace Aot\RussianSyntacsis\Sentence\SimpleSentence;

class SimpleSentence
{
    /** @var \Aot\RussianSyntacsis\Sentence\Member\Base[] */
    protected $members = [];
    /** @var \Aot\RussianSyntacsis\Sentence\SimpleSentence\Type\Base */
    protected $type;

    /**
     * @return SimpleSentence
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

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
     * @return Type\Base
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type\Base $type
     */
    public function setType(Type\Base $type)
    {
        $this->type = $type;
    }


}