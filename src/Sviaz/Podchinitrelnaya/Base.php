<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 14:27
 */

namespace Aot\Sviaz\Podchinitrelnaya;


class Base
{
    /** @var  string */
    protected $id;

    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $main_sequence_member;
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $depended_sequence_member;
    /** @var \Aot\Sviaz\Rule\Base */
    protected $rule;
    /** @var  \Aot\Sviaz\Sequence */
    protected $sequence;

    protected function __construct()
    {

    }

    /**
     * @return \Aot\Sviaz\Sequence
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Word\Base $main_sequence_member
     * @param \Aot\Sviaz\SequenceMember\Word\Base $depended_sequence_member
     * @param \Aot\Sviaz\Rule\Base $rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return Base
     */
    public static function create(
        \Aot\Sviaz\SequenceMember\Word\Base $main_sequence_member,
        \Aot\Sviaz\SequenceMember\Word\Base $depended_sequence_member,
        \Aot\Sviaz\Rule\Base $rule,
        \Aot\Sviaz\Sequence $sequence
    )
    {
        $ob = new static();

        $ob->main_sequence_member = $main_sequence_member;
        $ob->depended_sequence_member = $depended_sequence_member;
        $ob->rule = $rule;
        $ob->sequence = $sequence;
        // temporary start
        $ob->id = spl_object_hash($ob);
        // temporary end

        return $ob;
    }

    public static function createByDao()
    {
        throw new \RuntimeException("not implemented exception");
    }

    /**
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    public function getMainSequenceMember()
    {
        return $this->main_sequence_member;
    }

    /**
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    public function getDependedSequenceMember()
    {
        return $this->depended_sequence_member;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}