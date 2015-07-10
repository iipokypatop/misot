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
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;


class Container
{

    public static function getRule1()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже и глагол, совпадающий с ним в роде и числе,
то между ними есть связь. Порядок слов при этом может быть любой (глагол как перед существительным, так и после).
TEXT;

        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedChastRechi(ChastiRechiRegistry::GLAGOL)
            ->dependedRole(RoleRegistry::OTNOSHENIE);

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );

        $rule = $builder->get();

        return $rule;

    }

    public static function getRule2()
    {
        <<<TEXT
Если в предложении есть причастие и существительное и их формы совпадают в роде, числе и падеже, то между ним есть связь.
 Причастие может стоять как перед существительным, так и после него.
TEXT;
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedChastRechi(ChastiRechiRegistry::PRICHASTIE)
            ->dependedRole(RoleRegistry::SVOISTVO);

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADEJ
        );


        $rule = $builder->get();

        return $rule;
    }

    public static function getRule3()
    {
        <<<TEXT
Если после переходного глагола стоит существительное в винительном падеже,
то между ними есть связь.!УТОЧНИТЬ!
TEXT;
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::GLAGOL)
            ->mainMorphology(MorphologyRegistry::PEREHODNOST_PEREHODNII)
            ->mainRole(RoleRegistry::OTNOSHENIE)
            ->dependedAfterMain()
            ->dependedChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->dependedMorphology(MorphologyRegistry::PADEJ_VINITELNIJ)
            ->dependedRole(RoleRegistry::VESCH);

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule4()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже и прилагательное в именительном падеже,
совпадающее с существительным в числе и роде,
и между ними нет других существительных в именительном падеже – между ними есть связь.
TEXT;
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->dependedRole(RoleRegistry::SVOISTVO);


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );
        $builder->dependedAndMainCheck(
            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszheMezhduGlavnimIZavisimim
        );

        $rule = $builder->get();

        return $rule;
    }


    public static function getRule5()
    {
        <<<TEXT
Если в предложении есть прилагательное и сразу после него  – существительное,
и все они совпадают в роде, числе и падеже, то между ними есть связь.
TEXT;
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedRightBeforeMain()
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedRole(RoleRegistry::SVOISTVO);


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADEJ
        );

        $rule = $builder->get();

        return $rule;
    }



}