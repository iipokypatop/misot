<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 11:10
 */

namespace Aot\Sviaz;


class HomogeneitySupposed
{
    /** @var \Aot\Sviaz\SequenceMember\Base[] */
    protected $members = [];

    /**
     * Homogeneity constructor.
     */
    public function __construct($members)
    {
        $this->setMembers($members);
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     * @return static
     */
    public static function create(array $members = [])
    {
        return new static($members);
    }

    /**
     * @return SequenceMember\Base[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param SequenceMember\Base $member
     */
    public function addMember($member)
    {
        assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
        $this->members[spl_object_hash($member)] = $member;
    }

    /**
     * @param SequenceMember\Base[] $members
     */
    protected function setMembers($members)
    {
        $this->members = [];
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
            $this->members[spl_object_hash($member)] = $member;
        }
    }


}