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
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as CheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use AotTest\AotDataStorage;

class BuilderTest extends AotDataStorage
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
            ->dependedText("text text text")
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->dependedMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->dependedMorphology(MorphologyRegistry::ROD_MUZHSKOI);


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADEJ
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );
        $builder->dependedAndMainCheck(
            CheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim
        );

        $rule = $builder->get();

        var_export($rule);

    }
}