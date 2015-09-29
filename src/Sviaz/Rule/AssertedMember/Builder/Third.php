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


use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;

use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;

/**
 * Class Member
 * @package Aot\Sviaz\Rule\AssertedMember\Builder
 */
class Third extends Base
{

    /** @var $member \Aot\Sviaz\Rule\AssertedMember\Third */
    protected $member;

    protected $member_position = PositionRegistry::POSITION_ANY;
    protected $member_presence = PresenceRegistry::PRESENCE_PRESENT;

    /**
     * @param int $chast_rechi_id
     * @return Third
     */
    public static function create($chast_rechi_id)
    {
        assert(is_int($chast_rechi_id));

        if (empty(ChastiRechiRegistry::getClasses()[$chast_rechi_id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($chast_rechi_id, 1));
        }

        $ob = new static();

        $ob->chast_rechi_id = $chast_rechi_id;

        $ob->member = \Aot\Sviaz\Rule\AssertedMember\Third::create();

        $ob->member->assertChastRechi(
            ChastiRechiRegistry::getClasses()[$chast_rechi_id]
        );

        return $ob;
    }

    public function position($position_id)
    {
        assert(is_int($position_id));

        if (!in_array($position_id, [
            PositionRegistry::POSITION_ANY,
            PositionRegistry::POSITION_BETWEEN_MAIN_AND_DEPENDED,
            PositionRegistry::POSITION_AFTER_MAIN,
            PositionRegistry::POSITION_BEFORE_MAIN,
            PositionRegistry::POSITION_AFTER_DEPENDED,
            PositionRegistry::POSITION_BEFORE_DEPENDED,
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
     * @return \Aot\Sviaz\Rule\AssertedMember\Third
     */
    public function get()
    {
        return parent::get();
    }
}