<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 14:27
 */

namespace Aot\Sviaz\Homogeneity;

class Base
{
    /** @var \Aot\Sviaz\SequenceMember\Base[] */
    protected $members = [];

    /**
     * Homogeneity constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return \Aot\Sviaz\SequenceMember\Base[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base $member
     * @return void
     */
    public function addMember(\Aot\Sviaz\SequenceMember\Base $member)
    {
        $this->members[spl_object_hash($member)] = $member;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     */
    public function setMembers($members)
    {
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
            $this->addMember($member);
        }
    }
}