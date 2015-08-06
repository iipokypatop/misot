<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 13:47
 */

namespace AotTest\Functional\Sviaz\Rule;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry;
use Aot\Sviaz\Rule\AssertedMember\Builder\Base as AssertedMemberBuilder;
use Aot\Sviaz\Rule\AssertedLink\Builder\Base as AssertedLinkBuilder;

class Builder2Test extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::SVOISTVO)
                        ->text("text text text")
                        ->morphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        ->morphology(MorphologyRegistry::ROD_SREDNIJ)
                        ->check(MemberCheckerRegistry::PredlogPeredSlovom)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text(("bla bla bla"))
                        ->check(MemberCheckerRegistry::PredlogPeredSlovom)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        ->morphology(MorphologyRegistry::ROD_MUZHSKOI)
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::PADESZH
                        )
                        ->check(
                            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszhe
                        )
                        ->dependedAfterMain()
                );

        $builder->third(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Third::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Third::POSITION_AFTER_DEPENDED)
                ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
        );

        $rule = $builder->get();

        $this->assertInstanceOf(\Aot\Sviaz\Rule\Base::class, $rule);

        //print_r($rule);
    }


}