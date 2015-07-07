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
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $main_sequence_member;
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $depended_sequence_member;
    /** @var  \Aot\Sviaz\Role\Base */
    protected $main_role;
    /** @var  \Aot\Sviaz\Role\Base */
    protected $depended_role;

    /**
     * Base constructor.
     * @param Rule\AssertedMember\Main $main_sequence_member
     * @param Rule\AssertedMember\Depended $depended_sequence_member
     * @param Role\Base $main_role
     * @param Role\Base $depended_role
     */
    protected function __construct(Rule\AssertedMember\Main $main_sequence_member, Rule\AssertedMember\Depended $depended_sequence_member, Role\Base $main_role, Role\Base $depended_role)
    {
        $this->main_sequence_member = $main_sequence_member;
        $this->depended_sequence_member = $depended_sequence_member;
        $this->main_role = $main_role;
        $this->depended_role = $depended_role;
    }

    public static function create(Rule\AssertedMember\Main $main_sequence_member, Rule\AssertedMember\Depended $depended_sequence_member, Role\Base $main_role, Role\Base $depended_role)
    {
        return new static($main_sequence_member, $depended_sequence_member, $main_role, $depended_role);
    }
}