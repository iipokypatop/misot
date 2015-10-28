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
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     */
    protected function __construct(array $members)
    {
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
            $this->addMember($member);
        }
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     * @return static
     */
    public static function create(array $members)
    {
        return new static($members);
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

}