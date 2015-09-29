<?php
/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 10.07.15
 * Time: 11:52
 */

namespace Aot\Sviaz\Rule;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;
use Aot\Text\GroupIdRegistry;


class Container2
{
    public static function rule2()
    {

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->morphology(
                            MorphologyRegistry::PADESZH_IMENITELNIJ
                        )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
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

