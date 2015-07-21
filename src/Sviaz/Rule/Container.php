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
use \Aot\Sviaz\Rule\AssertedLink\Builder as AssertedLinkBuilder;


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

    protected static function getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu($mainMorphology, $dependedMorphology)
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
            'ImenitelniiImenitelnii' => [MorphologyRegistry::PADESZH_IMENITELNIJ, MorphologyRegistry::PADESZH_IMENITELNIJ],
            'ImenitelniiRoditelnii' => [MorphologyRegistry::PADESZH_IMENITELNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'ImenitelniiDatelnij' => [MorphologyRegistry::PADESZH_IMENITELNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'ImenitelniiTvoritelnij' => [MorphologyRegistry::PADESZH_IMENITELNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],
            'RoditelniiRoditelnii' => [MorphologyRegistry::PADESZH_RODITELNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'RoditelniiDatelnij' => [MorphologyRegistry::PADESZH_RODITELNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'RoditelniiTvoritelnij' => [MorphologyRegistry::PADESZH_RODITELNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],
            'DatelnijRoditelnii' => [MorphologyRegistry::PADESZH_DATELNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'DatelnijTvoritelnij' => [MorphologyRegistry::PADESZH_DATELNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],
            'VinitelnijRoditelnii' => [MorphologyRegistry::PADESZH_VINITELNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'VinitelnijDatelnij' => [MorphologyRegistry::PADESZH_VINITELNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'VinitelnijTvoritelnij' => [MorphologyRegistry::PADESZH_VINITELNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],
            'TvoritelnijRoditelnii' => [MorphologyRegistry::PADESZH_TVORITELNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'TvoritelnijDatelnij' => [MorphologyRegistry::PADESZH_TVORITELNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'PredlojnijRoditelnii' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'PredlojnijDatelnij' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'PredlojnijTvoritelnij' => [MorphologyRegistry::PADESZH_PREDLOZSHNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],


        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu($priznak[0], $priznak[1]);
        }
        return $rules;

    }


    public static function getRule6()
    {
        <<<TEXT
    Если в предложении есть личное местоимение в именительном падеже и глагол,
совпадающий с ним в роде и числе, то между ними есть связь.
Порядок слов может быть любым (глагол может быть как перед местоимением, так и после него).
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::MESTOIMENIE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::RAZRYAD_LICHNOE)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule7()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже,
глагол «быть»*, согласующийся в роде и числе с существительным,
а после него - существительное в именительном или творительном падеже, то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text("быть")

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );
        if (MorphologyRegistry::PADESZH_IMENITELNIJ) {
            $builder->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::SUSCHESTVITELNOE
                )
                    ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                    ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            );
        } elseif (MorphologyRegistry::PADESZH_TVORITELNIJ) {
            $builder->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::SUSCHESTVITELNOE
                )
                    ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                    ->morphology(MorphologyRegistry::PADESZH_TVORITELNIJ)
            );
        }

        $rule = $builder->get();

        return $rule;

    }

    public static function getRule8()
    {
        <<<TEXT
Если в предложении есть личное местоимение в именительном падеже, глагол «быть»,
согласующийся в роде и числе с местоимением,
а после него - существительное в именительном или творительном падеже,
то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::MESTOIMENIE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::RAZRYAD_LICHNOE)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text("быть")

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );

        if (MorphologyRegistry::PADESZH_IMENITELNIJ) {
            $builder->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::SUSCHESTVITELNOE
                )
                    ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                    ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            );
        } elseif (MorphologyRegistry::PADESZH_TVORITELNIJ) {
            $builder->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::SUSCHESTVITELNOE
                )
                    ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                    ->morphology(MorphologyRegistry::PADESZH_TVORITELNIJ)
            );
        }

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule9()
    {
        <<<TEXT
Если в предложении есть личное местоимение в именительном падеже, глагол «быть»,
согласующийся в роде и числе с местоимением,
а после него – прилагательное в полной форме в именительном или творительном падеже,
то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::MESTOIMENIE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::RAZRYAD_LICHNOE)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text("быть")

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );


        // wtf ??
//        if (MorphologyRegistry::PADESZH_IMENITELNIJ) {
        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::PRILAGATELNOE
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                ->morphology(MorphologyRegistry::FORMA_POLNAYA)
        );
//        }
        /*// wtf ??
        elseif (MorphologyRegistry::PADESZH_TVORITELNIJ) {
            $builder->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::PRILAGATELNOE
                )
                    ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)

                    ->morphology(MorphologyRegistry::PADESZH_TVORITELNIJ)
                    ->morphology(MorphologyRegistry::FORMA_POLNAYA)
            );
        }*/

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule10()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже и глагол «быть» в форме,
совпадающей с существительным в роде и числе, а после него – краткое страдательное причастие,
совпадающее с существительным в роде и числе, то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text("быть")

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );


        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::PRICHASTIE
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
                ->morphology(MorphologyRegistry::ZALOG_STRADATELNYJ)
                ->morphology(MorphologyRegistry::FORMA_KRATKAYA)


        );


        $rule = $builder->get();

        return $rule;
    }

    public static function getRule11()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже,
глагол «быть», совпадающий с существительным в роде и числе,
а также существительное в любом падеже, связанное с глаголом, то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->text("быть")

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );


        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED)
        );


        $rule = $builder->get();

        return $rule;
    }


    public static function getRule12()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже и прилагательное в именительном падеже,
совпадающее с существительным в числе и роде,
и между ними нет других существительных в именительном падеже – между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::VESCH)
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                        ->check(
                            LinkCheckerRegistry::NetSuschestvitelnogoVImenitelnomPadeszhe
                        )
                );
        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_NOT_PRESENT)
                ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
        );

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule13()
    {
        <<<TEXT
Если в предложении есть прилагательное
(или несколько прилагательных подряд через запятую или без неё)
и сразу после него (них) – существительное,
и все они совпадают в роде, числе и падеже, то между ними есть связь.
                ЧАСТИЧНО!!!
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::PRILAGATELNOE, RoleRegistry::SVOISTVO)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::PADESZH
                        )
                        ->dependedRightAfterMain()
                );

        $rule = $builder->get();

        return $rule;
    }

    public static function getRule14()
    {
        <<<TEXT
Если в предложении стоят подряд существительное,
а за ним – причастие, и они совпадают в роде, числе и падеже,
то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::SUSCHESTVITELNOE, RoleRegistry::VESCH)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::SVOISTVO
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::PADESZH
                        )
                );

        $rule = $builder->get();

        return $rule;
    }

    protected static function getRule16($priznak)
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::GLAGOL, RoleRegistry::OTNOSHENIE)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH

                    )
                        ->morphology($priznak)
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                       /*->morphologyMatching(
                            MorphologyRegistry::PADESZH
                        )*/
                        ->dependedRightAfterMain()
                );

        $builder
            ->member(
                \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                    ChastiRechiRegistry::GLAGOL
                )
                    ->notPresent() //->position(\Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_NOT_PRESENT)
            );

        $rule = $builder->get();

        return $rule;
    }

    public static function getRuleSuchPadej()
    {
        <<<TEXT
Если в предложении есть глагол и существительное в любом падеже,
кроме именительного, и между ними нет другого глагола, то между ними есть связь.
TEXT;
        $priznaki = [
            'Roditelnii' => MorphologyRegistry::PADESZH_RODITELNIJ,
            'Datelnij' => MorphologyRegistry::PADESZH_DATELNIJ,
            'Vinitelnij' => MorphologyRegistry::PADESZH_VINITELNIJ,
            'Tvoritelnij' => MorphologyRegistry::PADESZH_TVORITELNIJ,
            'Predlojnij' => MorphologyRegistry::PADESZH_PREDLOZSHNIJ
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getRule16($priznak);
        }
        return $rules;
    }

    public static function getRule17()
    {
        <<<TEXT
Если после переходного глагола
стоит личное местоимение в винительном падеже,
то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::GLAGOL, RoleRegistry::OTNOSHENIE)
                        ->morphology(MorphologyRegistry::PEREHODNOST_PEREHODNII)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                        ->morphology(MorphologyRegistry::PADESZH_VINITELNIJ)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedAfterMain()
                );


        $rule = $builder->get();

        return $rule;
    }

    public static function getRule15()
    {
        <<<TEXT
Если перед или после глагола есть деепричастие
и между ним нет других глаголов,
то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(ChastiRechiRegistry::GLAGOL, RoleRegistry::OTNOSHENIE)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::DEEPRICHASTIE,
                        RoleRegistry::SVOISTVO
                    )

                );

        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::GLAGOL
            )
                ->position(\Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_NOT_PRESENT)
        );

        $builder->link(
            AssertedLinkBuilder::create()
        );

        $rule = $builder->get();

        return $rule;
    }

    protected static function getRule_PerehGl_Susch()
    {
        <<<TEXT
Переходные глаголы обязаны иметь после себя существительное в Вин. Падеже с предлогом или без.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->morphology(MorphologyRegistry::PEREHODNOST_PEREHODNII)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                        ->morphology(MorphologyRegistry::PADESZH_VINITELNIJ)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedAfterMain()
                );

        $builder->link(
            AssertedLinkBuilder::create()
        );

        $rule = $builder->get();

        return $rule;
    }

    protected static function getRule_LichnoeMest_Pril(/*$padeszh, $rod, $chislo*/)
    {
        #TODO: местоимение должно стоять после или неважно?

        <<<TEXT
Если в предложении есть личное местоимение и прилагательное,
совпадающее с ним в роде, числе и падеже,
и между ними нет других местоимений или существительных,
совпадающих в роде, числе и падеже, то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::PADESZH
                        )
                );

        $builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE
            )
                ->notPresent()
            #TODO:
//                ->morphology($chislo)
//                ->morphology($rod)
//                ->morphology($padeszh)
        );


        $rule = $builder->get();

        return $rule;
    }

    /*public static function getPadeszh_Rule_LichnoeMest_Pril()
    {
        $padeszhi = [
            'Roditelnii' => MorphologyRegistry::PADESZH_RODITELNIJ,
            'Datelnij' => MorphologyRegistry::PADESZH_DATELNIJ,
            'Vinitelnij' => MorphologyRegistry::PADESZH_VINITELNIJ,
            'Tvoritelnij' => MorphologyRegistry::PADESZH_TVORITELNIJ,
            'Predlojnij' => MorphologyRegistry::PADESZH_PREDLOZSHNIJ
        ];


        $rules = [];
        foreach ($padeszhi as $name => $padeszh) {
            $rules[$name] = static::getRule_LichnoeMest_Pril($padeszh, $rod, $chislo);
        }
        return $rules;
    }*/


    /**
     * 81
     * @return static
     */
    public static function getRule_OtricMest_Gl(/*$padeszh, $rod, $chislo*/)
    {

        <<<TEXT
Если в предложении есть отрицательное местоимение никто, ничто в именительном падеже
и глагол в единственном числе и 3 лице настоящего, прошедшего или будущего времени
с частицей «не», то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        # TODO: добавить отрицательное местоимение
//                        ->morphology(MorphologyRegistry::RAZRYAD_OTRICATELNOE)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                    ->morphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
                    ->morphology(MorphologyRegistry::LITSO_TRETIE)

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        # TODO: добавить частицу
        /*$builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::CHASTICA
            )
                ->present()
        );*/


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 82
     * @return static
     */
    public static function getRule_OtricMest_Prich(/*$padeszh, $rod, $chislo*/)
    {

        <<<TEXT
Если в предложении есть отрицательное местоимение никто, ничто в именительном падеже
и краткое страдательное  причастие в единственном числе и 3 лице настоящего,
прошедшего или будущего времени, с частицей «не».
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                        ->morphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
                # TODO: добавить отрицательное местоимение
//                        ->morphology(MorphologyRegistry::RAZRYAD_OTRICATELNOE)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->morphology(MorphologyRegistry::FORMA_KRATKAYA)
                        ->morphology(MorphologyRegistry::ZALOG_STRADATELNYJ)
                        ->morphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
                        ->morphology(MorphologyRegistry::LITSO_TRETIE)

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        # TODO: добавить частицу
        /*$builder->member(
            \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                ChastiRechiRegistry::CHASTICA
            )
                ->present()
        );*/


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 83
     * @return static
     */
    public static function getRule_PrityazhMest_Susch(/*$padeszh, $rod, $chislo*/)
    {

        <<<TEXT
Если в предложении есть притяжательное местоимение 1 или 2 лица
(мой, моя, моё, мои, наш, наша, наше, наши, твой, твоя, твое, твои, ваш, ваша, ваше, ваши)
и существительное, совпадающее с ним в роде и падеже, то  между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::SVOISTVO
                    )
                        ->morphology(MorphologyRegistry::RAZRYAD_PRITIAZHATELNOE)
                    # TODO: вынести?
//                        ->morphology(MorphologyRegistry::LITSO_PERVOE)
//                        ->morphology(MorphologyRegistry::LITSO_VTOROE)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::ROD) // TODO: если единственное число
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                    # TODO: что насчет позиции?
//                        ->dependedBeforeMain()
                );



        $rule = $builder->get();

        return $rule;
    }

    /**
     * 84
     * @return static
     */
    public static function getRule_UkazMest_Susch(/*$padeszh, $rod, $chislo*/)
    {

        <<<TEXT
Если в предложении есть указательное местоимение
(тот, та, то, те, этот, эта, это, эти, таков, такова, таково, таковы, такой, такая, такое, такие и др.)
и существительное, совпадающее с ним в роде (в единственном числе), числе и падеже, то между ними есть связь.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::SVOISTVO
                    )
                        # TODO: добавить разряд
//                        ->morphology(MorphologyRegistry::RAZRYAD_UKAZATELNOE)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::ROD) // TODO: если единственное число
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                # TODO: что насчет позиции?
//                        ->dependedBeforeMain()
                );



        $rule = $builder->get();

        return $rule;
    }



}

