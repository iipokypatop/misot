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
     * @param \Aot\Sviaz\Sequence $sequence
     */
    protected function __construct(array $members, \Aot\Sviaz\Sequence $sequence)
    {
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
            $this->addMember($member, $sequence);
        }
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     * @param \Aot\Sviaz\Sequence $sequence
     * @return static
     */
    public static function create(array $members, \Aot\Sviaz\Sequence $sequence)
    {
        return new static($members, $sequence);
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
     * @param \Aot\Sviaz\Sequence $sequence
     * @return void
     */
    public function addMember(\Aot\Sviaz\SequenceMember\Base $member, \Aot\Sviaz\Sequence $sequence)
    {
        if (!is_null($sequence->getPosition($member))) {
            throw new \RuntimeException("member не принадлежит данному sequence");
        }
        $this->members[spl_object_hash($member)] = $member;
    }

}