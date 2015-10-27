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
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry;


class Container
{

    /**
     * 2
     * @return \Aot\Sviaz\Rule\Base
     */
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

    /**
     * 19
     * @return \Aot\Sviaz\Rule\Base
     */
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


    /**
     * 12
     * @return \Aot\Sviaz\Rule\Base
     */
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


    /**
     * 13
     * TODO: правило описано не полно (и текст правила не совсем совпадает)
     * @return \Aot\Sviaz\Rule\Base
     */
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


    /**
     * 3
     * @return \Aot\Sviaz\Rule\Base
     */
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
                        ->morphologyEq(MorphologyRegistry::RAZRYAD_LICHNOE)
                        ->morphologyEq(MorphologyRegistry::PADESZH_IMENITELNIJ)
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


    /**
     * 13
     * @return \Aot\Sviaz\Rule\Base
     */
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

    /**
     * 14
     * @return \Aot\Sviaz\Rule\Base
     */
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


    public static function getRuleSuchPadej()
    {
        throw new \RuntimeException("no more supported");

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

    /**
     * 16
     * @return \Aot\Sviaz\Rule\Base
     */
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
                        ->morphologyEq(MorphologyRegistry::PEREHODNOST_PEREHODNII)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                        ->morphologyEq(MorphologyRegistry::PADESZH_VINITELNIJ)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedAfterMain()
                );


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 33
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_PerehGl_Susch()
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
                        ->morphologyEq(MorphologyRegistry::PEREHODNOST_PEREHODNII)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                        ->morphologyEq(MorphologyRegistry::PADESZH_VINITELNIJ)

                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedAfterMain()
                );

        $rule = $builder->get();

        return $rule;
    }

    /**
     * 80
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_LichnoeMest_Pril()
    {
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


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 81
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_OtricMest_Gl()
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
                        ->morphologyEq(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        ->textGroupId(GroupIdRegistry::NIKTO)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->morphologyEq(MorphologyRegistry::CHISLO_EDINSTVENNOE)
                        ->morphologyEq(MorphologyRegistry::LITSO_TRETIE)
                        ->check(MemberCheckerRegistry::ChasticaNePeredSlovom)

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rule = $builder->get();


        return $rule;
    }


    /**
     * 82
     * @return \Aot\Sviaz\Rule\Base
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
                        ->morphologyEq(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        ->textGroupId(GroupIdRegistry::NIKTO)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->morphologyEq(MorphologyRegistry::FORMA_KRATKAYA)
                        ->morphologyEq(MorphologyRegistry::ZALOG_STRADATELNYJ)
                        ->morphologyEq(MorphologyRegistry::CHISLO_EDINSTVENNOE)
                        // у причастий нет лица!
                        //->morphology(MorphologyRegistry::LITSO_TRETIE)
                        ->check(MemberCheckerRegistry::ChasticaNePeredSlovom)

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rule = $builder->get();

        return $rule;
    }

    /**
     * 83
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_PrityazhMest_Susch()
    {

        // TODO: + совпадение по числу
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
                        // из морфика не приходит
//                        ->morphology(MorphologyRegistry::RAZRYAD_PRITYAZHATELNOE)
                        ->textGroupId(GroupIdRegistry::PRITYAZHATELNIE_1_AND_2_LITSO)
                )
                ->link(
                    AssertedLinkBuilder::create()
//                        ->morphologyMatching(MorphologyRegistry::ROD)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 84
     * @return \Aot\Sviaz\Rule\Base
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
                        // из морфика не приходит
//                        ->morphology(MorphologyRegistry::RAZRYAD_UKAZATELNOE)
                        ->textGroupId(GroupIdRegistry::UKAZATELNIE_MESTOIMENIYA)

                )
                ->link(
                    AssertedLinkBuilder::create()
//                        ->morphologyMatching(MorphologyRegistry::ROD)// TODO: если единственное число
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 79
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_GroupChisl_Susch()
    {

        <<<TEXT
Если в предложении есть несколько числительных, стоящих подряд и образующих неразделимое целое,
и существительное, совпадающее в роде и числе с последним в ряде числительных, то вместе
они образуют неделимое целое и являются одним членом предложения.
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
                        ChastiRechiRegistry::CHISLITELNOE,
                        RoleRegistry::SVOISTVO
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
//                        ->morphologyMatching(MorphologyRegistry::ROD)
//                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                );


        $rule = $builder->get();

        return $rule;
    }

    /**
     * 7 rewrite
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getSuschImenitPadeszh_Gl_Prich()
    {
        <<<TEXT
Если в предложении есть существительное в именительном падеже и глагол «быть» в форме,
совпадающей с существительным в роде и числе, а после него – краткое страдательное причастие,
совпадающее с существительным в роде и числе, то между ними есть связь.
TEXT;
        $priznaki = [
            'EdinstvennoeMuzhskoi' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI],
            'EdinstvennoeZhenskii' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII],
            'EdinstvennoeSrednij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ],
            'Mnozhestvennoe' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getRuleByRodAndChisloForSuschImenitPadeszh_Gl_Prich($priznak[0], $priznak[1]);
        }
        return $rules;
    }


    /**
     * 8
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getLichnoeMestImenitPadeszh_Gl_Prich()
    {
        <<<TEXT
Если в предложении есть личное местоимение в именительном падеже и глагол «быть» в форме,
совпадающей с существительным в роде и числе, а после него – краткое страдательное причастие,
совпадающее с существительным в роде и числе, то между ними есть связь.
TEXT;
        $priznaki = [
            'EdinstvennoeMuzhskoi' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI],
            'EdinstvennoeZhenskii' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII],
            'EdinstvennoeSrednij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ],
            'Mnozhestvennoe' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getRuleByRodAndChisloForLichnoeMestImenitPadeszh_Gl_Prich($priznak[0], $priznak[1]);
        }
        return $rules;
    }

    /**
     * 9
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Susch_GlBit_GlInf()
    {

        // инфинитив = не привязанное к субъекту (лицу, числу, наклонению) и ко времени.
        <<<TEXT
Если  предложении есть существительное в именительном падеже, глагол-связка «быть»,
совпадающий с существительным в роде и числе, а после него – глагол в инфинитиве,
то между ними есть связь.
TEXT;
        $priznaki = [
            'EdinstvennoeMuzhskoi' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI],
            'EdinstvennoeZhenskii' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII],
            'EdinstvennoeSrednij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ],
            'Mnozhestvennoe' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getByRodAndChisloForRule_Susch_GlBit_GlInf($priznak[0], $priznak[1]);
        }
        return $rules;
    }


    /**
     * 10
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Mest_Gl_Narech()
    {

        // инфинитив = не привязанное к субъекту (лицу, числу, наклонению) и ко времени.
        <<<TEXT
Если  предложении есть существительное в именительном падеже, глагол-связка «быть»,
совпадающий с существительным в роде и числе, а после него – глагол в инфинитиве,
то между ними есть связь.
TEXT;
        $priznaki = [
            'EdinstvennoeMuzhskoi' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI],
            'EdinstvennoeZhenskii' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII],
            'EdinstvennoeSrednij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ],
            'Mnozhestvennoe' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {

            $rules[$name] = static::getByRodAndChisloForRule_Mest_Gl_Narech($priznak[0], $priznak[1]);
        }
        return $rules;
    }


    /**
     * 18
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function Rule_Gl_Deepr()
    {
        <<<TEXT
Если в предложении есть деепричастие и глагол в любой форме, то между ними есть связь.
При этом глагол может быть как после деепричастия, так и перед ним.
TEXT;

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::DEEPRICHASTIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );


        $rule = $builder->get();

        return $rule;
    }

    /**
     * Прогон правил по роду и числу
     * @param string $name_rule название правила
     * @return array
     */
    protected static function runChisloRod($name_rule)
    {

        if (!method_exists(self::class, $name_rule)) {
            throw new \RuntimeException('Правила ' . $name_rule . ' не существует');
        }

        $priznaki = [
            'EdinstvennoeMuzhskoi' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI],
            'EdinstvennoeZhenskii' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII],
            'EdinstvennoeSrednij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ],
            'Mnozhestvennoe' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {
            $rules[$name] = static::$name_rule($priznak[0], $priznak[1]);
        }
        return $rules;
    }

    /**
     * Прогон правил по роду, числу и падежу
     * @param string $name_rule название правила
     * @return array
     */
    protected static function runChisloRodPadeszh($name_rule)
    {

        if (!method_exists(self::class, $name_rule)) {
            throw new \RuntimeException('Правила ' . $name_rule . ' не существует');
        }

        $priznaki = [
            'EdinstvennoeMuzhskoiImenitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_IMENITELNIJ],
            'EdinstvennoeMuzhskoiRoditelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_RODITELNIJ],
            'EdinstvennoeMuzhskoiVinitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_VINITELNIJ],
            'EdinstvennoeMuzhskoiDatelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_DATELNIJ],
            'EdinstvennoeMuzhskoiPredlozshnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_PREDLOZSHNIJ],
            'EdinstvennoeMuzhskoiTvoritelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_MUZHSKOI, MorphologyRegistry::PADESZH_TVORITELNIJ],

            'EdinstvennoeZhenskiiImenitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_IMENITELNIJ],
            'EdinstvennoeZhenskiiRoditelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_RODITELNIJ],
            'EdinstvennoeZhenskiiVinitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_VINITELNIJ],
            'EdinstvennoeZhenskiiDatelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_DATELNIJ],
            'EdinstvennoeZhenskiiPredlozshnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_PREDLOZSHNIJ],
            'EdinstvennoeZhenskiiTvoritelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_ZHENSKII, MorphologyRegistry::PADESZH_TVORITELNIJ],

            'EdinstvennoeSrednijImenitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_IMENITELNIJ],
            'EdinstvennoeSrednijRoditelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_RODITELNIJ],
            'EdinstvennoeSrednijVinitelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_VINITELNIJ],
            'EdinstvennoeSrednijDatelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_DATELNIJ],
            'EdinstvennoeSrednijPredlozshnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_PREDLOZSHNIJ],
            'EdinstvennoeSrednijTvoritelnij' => [MorphologyRegistry::CHISLO_EDINSTVENNOE, MorphologyRegistry::ROD_SREDNIJ, MorphologyRegistry::PADESZH_TVORITELNIJ],

            'MnozhestvennoeImenitelnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_IMENITELNIJ],
            'MnozhestvennoeRoditelnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_RODITELNIJ],
            'MnozhestvennoeVinitelnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_VINITELNIJ],
            'MnozhestvennoeDatelnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_DATELNIJ],
            'MnozhestvennoePredlozshnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_PREDLOZSHNIJ],
            'MnozhestvennoeTvoritelnij' => [MorphologyRegistry::CHISLO_MNOZHESTVENNOE, null, MorphologyRegistry::PADESZH_TVORITELNIJ],
        ];


        $rules = [];
        foreach ($priznaki as $name => $priznak) {
            $rules[$name] = static::$name_rule($priznak[0], $priznak[1], $priznak[2]);
        }
        return $rules;
    }

    /**
     * 34
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_LichnoeMest_GlagBit_KrPril()
    {

        <<<TEXT
Если в предложении есть личное местоимение в именительном падеже, глагол «быть»,
согласующийся в числе, роде (в случае единственного числа) и лице с местоимением,
а после него – прилагательное в краткой форме, совпадающее с ними в числе и роде
(в случае единственного числа), то между ними есть связь.
TEXT;
        return static::runChisloRod('getByRodAndChisloForRule_LichnoeMest_GlagBit_KrPril');
    }


    /**
     * 35
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Susch_GlagBit_KrPril()
    {

        <<<TEXT
Если в предложении есть личное местоимение в именительном падеже, глагол «быть»,
согласующийся в числе, роде (в случае единственного числа) и лице с местоимением,
а после него – прилагательное в краткой форме, совпадающее с ними в числе и роде
(в случае единственного числа), то между ними есть связь.
TEXT;
        return static::runChisloRod('getByRodAndChisloForRule_Susch_GlagBit_KrPril');
    }


    /**
     * 37 fail
     * @return \Aot\Sviaz\Rule\Base
     */
    /*public static function getRule_KrPril_Susch()
    {

        <<<TEXT
Если в предложении есть краткое прилагательное и существительное, совпадающее с ним в роде и числе и
между ними нет других существительных, совпадающих в роде и числе, то между ними есть связь.
TEXT;
        return static::runChisloRodPadeszh('getByRodAndChisloForRule_KrPril_Susch');
    }*/


    /**
     * 37
     * @return \Aot\Sviaz\Rule\Base
     */
    /*protected static function getByRodAndChisloForRule_KrPril_Susch($chislo, $rod, $padeszh)
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                        ->morphology($padeszh)
                        ->morphology($chislo)
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )
                        ->morphology($padeszh)
//                        ->morphology(MorphologyRegistry::FORMA_KRATKAYA)
                        ->morphology($chislo)
                )
                // TODO: не срабатывает, и обязательно дб third??
                ->third(
                    $builder_member = \Aot\Sviaz\Rule\AssertedMember\Builder\Member::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE
                    )
                        ->morphology($padeszh)
                        ->morphology($chislo)
                        ->notPresent()
                        ->position(\Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BETWEEN_MAIN_AND_DEPENDED)

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        if ($rod !== null) {
            $builder_main->morphology($rod);
            $builder_depended->morphology($rod);
//            $builder_member->morphology($rod);
        }

        $rule = $builder->get();

        return $rule;
    }*/


    /**
     * 39
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Susch_PoryadkChisl()
    {

        <<<TEXT
 Если в предложении есть существительное и порядковое числительное, и они совпадают в роде,
числе и падеже и между ними нет других существительных в том же роде, числе и падеже, то между ними есть связь.
TEXT;
        return static::runChisloRodPadeszh('getByRodAndChisloForRule_Susch_PoryadkChisl');
    }


    /**
     * 23
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Pril_Narech()
    {
        <<<TEXT
С прилагательными образуют связь следующие наречия:...
Между прилагательным и таким наречием не бывает других слов.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::SVOISTVO
                    )
                        ->textGroupId(GroupIdRegistry::NARECH_FOR_PRIL_OR_NARECH)
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedRightBeforeMain()
                );

        $rule = $builder->get();

        return $rule;
    }

    /**
     * 24
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Narech_Narech()
    {
        <<<TEXT
С наречиями образуют связь следующие наречия:...
Между наречиями и таким наречием не бывает других слов.
TEXT;
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::SVOISTVO
                    )
                        ->textGroupId(GroupIdRegistry::NARECH_FOR_PRIL_OR_NARECH)
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->dependedRightBeforeMain()
                );

        $rule = $builder->get();

        return $rule;
    }

    /**
     * 25
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Gl_Narech()
    {
        <<<TEXT
С глаголами образуют связь следующие наречия:...
TEXT;
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
                        ->textGroupId(GroupIdRegistry::NARECH_FOR_GL)
                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rule = $builder->get();

        return $rule;
    }

    /**
     * 26
     * @return \Aot\Sviaz\Rule\Base
     */
    public static function getRule_Gl_DefisNarech()
    {
        <<<TEXT
С глаголами образуют связь следующие наречия, которые пишутся через дефис:...
TEXT;
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
//                        ->textGroupId(GroupIdRegistry::DEFISNARECH_FOR_GL)
                )
                ->link(
                    AssertedLinkBuilder::create()
//                        ->dependedRightBeforeMain()
                );

        $rule = $builder->get();

        return $rule;
    }


}

