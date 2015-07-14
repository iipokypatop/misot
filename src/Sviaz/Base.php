<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 07.07.2015
 * Time: 13:57
 */

namespace Aot\Sviaz;


class Base
{
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $main_sequence_member;
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $depended_sequence_member;
    /** @var  string */
    protected $main_role_class;
    /** @var  string */
    protected $depended_role_class;

    /**
     * @param SequenceMember\Base $main_sequence_member
     * @param SequenceMember\Base $depended_sequence_member
     * @param $main_role_class
     * @param $depended_role_class
     */
    protected function __construct(SequenceMember\Base $main_sequence_member, SequenceMember\Base $depended_sequence_member, $main_role_class, $depended_role_class)
    {
        $this->main_sequence_member = $main_sequence_member;
        $this->depended_sequence_member = $depended_sequence_member;
        $this->main_role_class = $main_role_class;
        $this->depended_role_class = $depended_role_class;
    }

    public static function create(SequenceMember\Base $main_sequence_member, SequenceMember\Base $depended_sequence_member, $main_role_class, $depended_role_class)
    {
        return new static($main_sequence_member, $depended_sequence_member, $main_role_class, $depended_role_class);
    }

    /**
     * @return SequenceMember\Base
     */
    public function getMainSequenceMember()
    {
        return $this->main_sequence_member;
    }

    /**
     * @return SequenceMember\Base
     */
    public function getDependedSequenceMember()
    {
        return $this->depended_sequence_member;
    }

    /**
     * @return string
     */
    public function getMainRoleClass()
    {
        return $this->main_role_class;
    }

    /**
     * @return string
     */
    public function getDependedRoleClass()
    {
        return $this->depended_role_class;
    }
}