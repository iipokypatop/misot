<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 13:53
 */

namespace Aot\Sviaz\Rule\AssertedMember\Builder;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;


use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;

use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;

/**
 * Class Member
 * @package Aot\Sviaz\Rule\AssertedMember\Builder
 */
class Member extends Base
{

    /** @var $member \Aot\Sviaz\Rule\AssertedMember\Member */
    protected $member;

    protected $member_position = \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_ANY;
    protected $member_presence = \Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_PRESENT;

    /**
     * @param int $chast_rechi_id
     * @return Member
     */
    public static function create($chast_rechi_id)
    {
        assert(is_int($chast_rechi_id));

        if (empty(ChastiRechiRegistry::getClasses()[$chast_rechi_id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($chast_rechi_id, 1));
        }

        $ob = new static();

        $ob->chast_rechi_id = $chast_rechi_id;

        $ob->member = \Aot\Sviaz\Rule\AssertedMember\Member::create();

        $ob->member->assertChastRechi(
            ChastiRechiRegistry::getClasses()[$chast_rechi_id]
        );

        return $ob;
    }

    public function position($position_id)
    {
        assert(is_int($position_id));

        if (!in_array($position_id, [
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_ANY,
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BETWEEN_MAIN_AND_DEPENDED,
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_MAIN,
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BEFORE_MAIN,
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED,
            \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BEFORE_DEPENDED,
        ], true)
        ) {
            throw new \RuntimeException("unsupported position_id = $position_id");
        }

        $this->member->setPosition($position_id);

        return $this;
    }

    public function present()
    {
        $this->member->present();

        return $this;
    }

    public function notPresent()
    {
        $this->member->notPresent();

        return $this;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Member
     */
    public function get()
    {
        return parent::get();
    }
}