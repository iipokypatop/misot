<?php
/**
 * Created by PhpStorm.
 * User: Peter Semenyuk
 * Date: 014, 14, 04, 2016
 * Time: 11:39
 */

namespace Aot\Sviaz\Rule\Container\Section;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry;


class Glagol extends Base
{
    public static function create()
    {
        $ob = new static;

        return $ob;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public function getRules()
    {
        $rules = [];

        $rules[] = $this->_1();

        return $rules;
    }


    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function _1()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rule = $builder->get();

        return $rule;
    }
}