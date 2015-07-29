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
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $main_sequence_member;
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $depended_sequence_member;
    /** @var \Aot\Sviaz\Rule\Base */
    protected $rule;

    /**
     * Base constructor.
     * @param \Aot\Sviaz\SequenceMember\Base $main_sequence_member
     * @param \Aot\Sviaz\SequenceMember\Base $depended_sequence_member
     * @param \Aot\Sviaz\Rule\Base $rule
     */
    protected function __construct(\Aot\Sviaz\SequenceMember\Base $main_sequence_member, \Aot\Sviaz\SequenceMember\Base $depended_sequence_member, \Aot\Sviaz\Rule\Base $rule)
    {
        $this->main_sequence_member = $main_sequence_member;
        $this->depended_sequence_member = $depended_sequence_member;
        $this->rule = $rule;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base $main_sequence_member
     * @param \Aot\Sviaz\SequenceMember\Base $depended_sequence_member
     * @param \Aot\Sviaz\Rule\Base $rule
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    public static function create(\Aot\Sviaz\SequenceMember\Base $main_sequence_member, \Aot\Sviaz\SequenceMember\Base $depended_sequence_member, \Aot\Sviaz\Rule\Base $rule)
    {
        return new static($main_sequence_member, $depended_sequence_member, $rule);
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



}