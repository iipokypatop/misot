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


class BuilderTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainText("bla bla bla")
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->mainMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->mainMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->mainMorphology(MorphologyRegistry::ROD_SREDNIJ)
            ->mainRole(RoleRegistry::SVOISTVO)
            ->dependedText("text text text")
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->dependedMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->dependedMorphology(MorphologyRegistry::ROD_MUZHSKOI)
            ->dependedRole(RoleRegistry::OTNOSHENIE)
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE);

        $builder
            ->dependedAfterMain()
            ->dependedBeforeMain()
            ->dependedRightAfterMain()
            ->dependedRightBeforeMain();


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADESZH
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );
        $builder->dependedAndMainCheck(
            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszhe
        );

/*
        $builder->tretieSlovoMezhduGlavnimIZavisimim(

        );
*/

        $rule = $builder->get();

        //var_export($rule);
    }

    public function testGroupId_success()
    {
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainRole(RoleRegistry::SVOISTVO)
            ->mainTextGroupId(GroupIdRegistry::BIT)
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedRole(RoleRegistry::OTNOSHENIE)
            ->dependedTextGroupId(GroupIdRegistry::BIT);

        $rule = $builder->get();

        //var_export($rule);
    }

    public function testGroupId_throw_exception()
    {
        $id = 0;

        try {
            \Aot\Sviaz\Rule\Builder::create()
                ->mainTextGroupId($id);
            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals("unsupported text_group_group_id = " . $id, $e->getMessage());
        }

        try {
            \Aot\Sviaz\Rule\Builder::create()
                ->dependedTextGroupId($id);
            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals("unsupported text_group_group_id = " . $id, $e->getMessage());
        }
    }
}