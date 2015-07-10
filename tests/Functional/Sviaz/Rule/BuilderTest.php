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


class BuilderTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainText("bla bla bla")
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->mainMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->mainMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->mainMorphology(MorphologyRegistry::ROD_SREDNIJ)
            ->mainRole(RoleRegistry::SVOISTVO)
            ->dependedText("text text text")
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->dependedMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->dependedMorphology(MorphologyRegistry::ROD_MUZHSKOI)
            ->dependedRole(RoleRegistry::OTNOSHENIE)
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE);

        $builder->dependedAfterMain();
        $builder->dependedBeforeMain();
        $builder->dependedRightAfterMain();
        $builder->dependedRightBeforeMain();


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADEJ
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );
        $builder->dependedAndMainCheck(
            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim
        );

        $rule = $builder->get();

    }
}