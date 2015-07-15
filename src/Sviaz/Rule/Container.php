<?php
/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 10.07.15
 * Time: 11:52
 */

namespace Aot\Sviaz\Rule;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Povelitelnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Yslovnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj;
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
            ->mainMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->mainMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedChastRechi(ChastiRechiRegistry::GLAGOL)
            ->dependedMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->dependedRole(RoleRegistry::OTNOSHENIE);

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
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
            MorphologyRegistry::PADESZH
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
            ->dependedMorphology(MorphologyRegistry::PADESZH_VINITELNIJ)
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
            ->mainMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->dependedRole(RoleRegistry::SVOISTVO);


        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::ROD
        );

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::CHISLO
        );
        $builder->dependedAndMainCheck(
            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszhe
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
            MorphologyRegistry::PADESZH
        );

        $rule = $builder->get();

        return $rule;
    }

    public static function getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu($mainMorphology, $dependedMorphology)
    {
        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainMorphology($mainMorphology)
            ->mainRole(RoleRegistry::VESCH)
            ->dependedRightAfterMain()
            ->dependedChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->dependedMorphology($dependedMorphology)
            ->dependedRole(RoleRegistry::VESCH);

        $rule = $builder->get();

        return $rule;
    }

    public static function getRuleSuch1()
    {
        <<<TEXT
существительное (* падеж) + существительное (* падеж)
TEXT;
        $priznaki = [
            'ImenitelniiImenitelnii' => [MorphologyRegistry::PADESZH_IMENITELNIJ,MorphologyRegistry::PADESZH_IMENITELNIJ],
            'ImenitelniiRoditelnii' => [MorphologyRegistry::PADESZH_IMENITELNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'ImenitelniiDatelnij' => [MorphologyRegistry::PADESZH_IMENITELNIJ,MorphologyRegistry::PADESZH_DATELNIJ],
            'ImenitelniiTvoritelnij' => [MorphologyRegistry::PADESZH_IMENITELNIJ,MorphologyRegistry::PADESZH_TVORITELNIJ],
            'RoditelniiRoditelnii' => [MorphologyRegistry::PADESZH_RODITELNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'RoditelniiDatelnij' => [MorphologyRegistry::PADESZH_RODITELNIJ,MorphologyRegistry::PADESZH_DATELNIJ],
            'RoditelniiTvoritelnij' => [MorphologyRegistry::PADESZH_RODITELNIJ,MorphologyRegistry::PADESZH_TVORITELNIJ],
            'DatelnijRoditelnii' => [MorphologyRegistry::PADESZH_DATELNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'DatelnijTvoritelnij' => [MorphologyRegistry::PADESZH_DATELNIJ,MorphologyRegistry::PADESZH_TVORITELNIJ],
            'VinitelnijRoditelnii' => [MorphologyRegistry::PADESZH_VINITELNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'VinitelnijDatelnij' => [MorphologyRegistry::PADESZH_VINITELNIJ,MorphologyRegistry::PADESZH_DATELNIJ],
            'VinitelnijTvoritelnij' => [MorphologyRegistry::PADESZH_VINITELNIJ,MorphologyRegistry::PADESZH_TVORITELNIJ],
            'TvoritelnijRoditelnii' => [MorphologyRegistry::PADESZH_TVORITELNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'TvoritelnijDatelnij' => [MorphologyRegistry::PADESZH_TVORITELNIJ,MorphologyRegistry::PADESZH_DATELNIJ],
            'PredlojnijRoditelnii' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ,MorphologyRegistry::PADESZH_RODITELNIJ],
            'PredlojnijDatelnij' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ,MorphologyRegistry::PADESZH_DATELNIJ],
            'PredlojnijTvoritelnij' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ,MorphologyRegistry::PADESZH_TVORITELNIJ],


        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu($priznak[0], $priznak[1]);
        }
        return $rules;

    }
}