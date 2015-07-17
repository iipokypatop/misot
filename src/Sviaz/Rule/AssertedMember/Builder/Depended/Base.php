<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 13:54
 */

namespace Aot\Sviaz\Rule\AssertedMember\Builder\Depended;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;


class Base extends \Aot\Sviaz\Rule\AssertedMember\Builder\Base
{
    /**
     * @param int $chast_rechi_id
     * @param int $role_id
     * @return \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base
     */
    public static function create($chast_rechi_id, $role_id)
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

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Depended
     */
    public function get()
    {
        return parent::get();
    }
}