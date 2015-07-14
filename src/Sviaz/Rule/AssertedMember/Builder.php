<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 13.07.2015
 * Time: 15:03
 */

namespace Aot\Sviaz\Rule\AssertedMember;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;


use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;


class Builder
{
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Base */
    protected $member;

    /** @var  int */
    protected $chast_rechi_id;


    protected static function create()
    {
        return new static();
    }

    /**
     * @param int $chast_rechi_id
     * @param int $role_id
     * @return static
     */
    public static function main($chast_rechi_id, $role_id)
    {
        assert(is_int($chast_rechi_id));
        assert(is_int($role_id));

        if (empty(ChastiRechiRegistry::getClasses()[$chast_rechi_id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($chast_rechi_id, 1));
        }

        if (empty(RoleRegistry::getClasses()[$role_id])) {
            throw new \RuntimeException("unsupported role id $role_id");
        }

        $ob = new static();

        $ob->chast_rechi_id = $chast_rechi_id;

        $ob->member = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $ob->member->assertChastRechi(
            ChastiRechiRegistry::getClasses()[$chast_rechi_id]
        );

        $ob->member->setRoleClass(
            RoleRegistry::getClasses()[$role_id]
        );

        return $ob;
    }


    /**
     * @param int $chast_rechi_id
     * @param int $role_id
     * @return static
     */
    public static function depended($chast_rechi_id, $role_id)
    {
        assert(is_int($chast_rechi_id));
        assert(is_int($role_id));

        if (empty(ChastiRechiRegistry::getClasses()[$chast_rechi_id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($chast_rechi_id, 1));
        }

        if (empty(RoleRegistry::getClasses()[$role_id])) {
            throw new \RuntimeException("unsupported role id $role_id");
        }

        $ob = new static();

        $ob->chast_rechi_id = $chast_rechi_id;

        $ob->member = \Aot\Sviaz\Rule\AssertedMember\Depended::create();

        $ob->member->assertChastRechi(
            ChastiRechiRegistry::getClasses()[$chast_rechi_id]
        );

        $ob->member->setRoleClass(
            RoleRegistry::getClasses()[$role_id]
        );

        return $ob;
    }

    const POSITION_BETWEEN_MAIN_AND_DEPENDED = 1;
    const POSITION_AFTER_MAIN = 2;
    const POSITION_BEFORE_MAIN = 3;
    const POSITION_AFTER_DEPENDED = 4;
    const POSITION_BEFORE_DEPENDED = 5;


    protected $member_position;

    /**
     * @param int $chast_rechi_id
     * @param int $position_id
     * @return static
     */
    public static function member($chast_rechi_id, $position_id)
    {
        assert(is_int($chast_rechi_id));
        assert(is_int($position_id));


        if (empty(ChastiRechiRegistry::getClasses()[$chast_rechi_id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($chast_rechi_id, 1));
        }

        if (!in_array($position_id, [
            static::POSITION_BETWEEN_MAIN_AND_DEPENDED,
            static::POSITION_AFTER_MAIN,
            static::POSITION_BEFORE_MAIN,
            static::POSITION_AFTER_DEPENDED,
            static::POSITION_BEFORE_DEPENDED,
        ], true)
        ) {
            throw new \RuntimeException("unsupported position_id = $position_id");
        }

        $ob = new static();

        $ob->chast_rechi_id = $chast_rechi_id;

        $ob->member_position = $position_id;

        $ob->member = \Aot\Sviaz\Rule\AssertedMember\Base::create();

        $ob->member->assertChastRechi(
            ChastiRechiRegistry::getClasses()[$chast_rechi_id]
        );

        return $ob;
    }


    /**
     * @param string $text
     * @return $this
     */
    public function text($text)
    {
        $this->member->assertText($text);

        return $this;
    }

    public function textGroupId($id)
    {
        $this->member->assertTextGroupId($id);

        return $this;
    }

    public function morphology($id)
    {
        assert(is_int($id));

        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $class = MorphologyRegistry::getClassByChastRechiAndPriznak(
            $this->chast_rechi_id,
            $id
        );

        if (is_null($class)) {
            throw new \RuntimeException("unsupported morphology id = $id for chast rechi_id = $this->chast_rechi_id");
        }

        $this->member->assertMorphology(
            $class
        );

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function check($id)
    {
        assert(is_int($id));

        if (empty(MemberCheckerRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id = " . $id);
        }

        $this->member->addChecker(
            MemberCheckerRegistry::getClasses()[$id]
        );

        return $this;

    }


    public function get()
    {
        $member = $this->member;

        $this->member = null;

        return $member;
    }
}