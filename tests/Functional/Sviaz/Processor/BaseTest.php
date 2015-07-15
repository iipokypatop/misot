<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:47
 */

namespace AotTest\Functional\Sviaz\Processor;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\RussianMorphology\ChastiRechi\Predlog\Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Soyuz;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use MivarTest\PHPUnitHelper;


class BaseTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $processor = \Aot\Sviaz\Processor\Base::create();

        $rule = $this->getRule1();
        $rule = $this->getRule2();

        $link_container = $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule]
        );

        $link_container = array_filter($link_container);

        foreach ($link_container as $sequence_index => $links) {
            $data = [];
            $data [] = $sequence_index;

            foreach ($links as $link) {
                $data[] =
                    $link->getMainSequenceMember()->getSlovo()->getText()
                    . "->" .
                    $link->getDependedSequenceMember()->getSlovo()->getText();
            }

            echo join(" ", $data) . "\n";
        }
    }

    protected function getRule2()
    {

        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->mainMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->mainMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->mainMorphology(MorphologyRegistry::ROD_SREDNIJ)
            ->mainRole(RoleRegistry::SVOISTVO)
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->dependedMorphology(MorphologyRegistry::PADEJ_IMENITELNIJ)
            ->dependedMorphology(MorphologyRegistry::ROD_MUZHSKOI)
            ->dependedRole(RoleRegistry::OTNOSHENIE);

        // $builder->s

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADEJ
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
     * @return \Aot\Sviaz\Rule\AssertedMember\Main
     */
    protected function get_asserted_main()
    {
        $asserted_main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        $asserted_main->assertChastRechi(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class
        );

        $asserted_main->assertMorphology(
            new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij
        );

        $asserted_main->setRole(
            \Aot\Sviaz\Role\Vesch::create()
        );

        return $asserted_main;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Depended
     */
    protected function get_asserted_depended()
    {
        $asserted_depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $asserted_depended->assertChastRechi(
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class
        );

        $asserted_depended->assertMorphology(
            new \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij
        );

        $asserted_depended->setRole(
            \Aot\Sviaz\Role\Svoistvo::create()
        );

        return $asserted_depended;
    }

    protected function get_asserted_link($rule)
    {
        $link = \Aot\Sviaz\Rule\AssertedLink\Base::create($rule);

        // падеж
        $asserted_matching[0] = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
            SuschestvitelnoePadeszhBase::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class
        );

        // род
        $asserted_matching[1] = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base::class
        );

        // число
        $asserted_matching[2] = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base::class
        );

        $link->addAssertedMatching($asserted_matching[0]);
        $link->addAssertedMatching($asserted_matching[1]);
        $link->addAssertedMatching($asserted_matching[2]);

        return $link;
    }

    protected function getRule1()
    {
        <<<RULE
        Если в предложении стоят подряд существительное, а за ним – причастие, и они совпадают в роде, числе и падеже, то между ними есть связь.
RULE;
        $asserted_main = $this->get_asserted_main();
        $asserted_depended = $this->get_asserted_depended();


        $rule = \Aot\Sviaz\Rule\Base::create(
            $asserted_main,
            $asserted_depended
        );

        $link = $this->get_asserted_link($rule);

        $rule->addLink($link);

        $link->addChecker(
            \Aot\Sviaz\Rule\AssertedLink\Checker\BeetweenMainAndDepended\NetSuschestvitelnogoVImenitelnomPadeszhe::create()
        );


        return $rule;
    }


    public function testFirst()
    {
        #   $this->markTestSkipped();

        $processor = \Aot\Sviaz\Processor\Base::create();

        $rule = $this->getRule1();


        $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule]
        );
    }

    public function testSecond()
    {
        $processor = \Aot\Sviaz\Processor\Base::create();


        $link_container = $processor->go(
            $this->getNormalizedMatrix1(),
            array_merge([
                \Aot\Sviaz\Rule\Container::getRule1(),
                \Aot\Sviaz\Rule\Container::getRule2(),
                \Aot\Sviaz\Rule\Container::getRule3(),
                \Aot\Sviaz\Rule\Container::getRule4(),
                \Aot\Sviaz\Rule\Container::getRule5(),
            //\Aot\Sviaz\Rule\Container::getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu(),
            ], \Aot\Sviaz\Rule\Container::getRuleSuch1()
            )
        );

        foreach ($link_container as $sequence_index => $links) {
            $data = [];
            $data [] = $sequence_index;

            foreach ($links as $link) {
                $data[] =
                    $link->getMainSequenceMember()->getSlovo()->getText()
                    . "->" .
                    $link->getDependedSequenceMember()->getSlovo()->getText();
            }

            echo join(" ", $data) . "\n";
        }

    }

    /**
     * @return array
     */
    protected function getWordsAndPunctuation1()
    {
        <<<TEXT
Над горами появились облака – сначала легкие и воздушные, затем серые, с рваными краями
TEXT;
        //$nad[0] = $this->getSafeMockLocal1(Predlog::class, ['__set', 'getMorphology', '__get', 'getMorphologyByClass_TEMPORARY']);
        $nad[0] = $this->getMock(Predlog::class, ['_']);
        PHPUnitHelper::setProtectedProperty($nad[0], 'text', 'Над');

        # $gorami[0] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $gorami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($gorami[0], 'text', 'горами');


        $gorami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $gorami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $gorami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $gorami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $gorami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::create();
        $gorami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        /*
        'chislo' => Morphology\Chislo\Base::class,
        'naritcatelnost' => Morphology\Naritcatelnost\Base::class,
        'odushevlyonnost' => Morphology\Odushevlyonnost\Base::class,
        'padeszh' => Morphology\Padeszh\Base::class,
        'rod' => Morphology\Rod\Base::class,
        'sklonenie' => Morphology\Sklonenie\Base::class,
        'vozvratnost' => Morphology\Vozvratnost\Base::class,
        'vremya' => Morphology\Vremya\Base::class,
        'zalog' => Morphology\Zalog\Base::class,
        */

        //$poiavilis[0] = $this->getSafeMockLocal1(Glagol::class);
        $poiavilis[0] = $this->getMock(Glagol::class, ['_']);
        PHPUnitHelper::setProtectedProperty($poiavilis[0], 'text', 'появились');

        $poiavilis[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::create();
        $poiavilis[0]->litso = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::create();
        $poiavilis[0]->naklonenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe::create();
        $poiavilis[0]->perehodnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::create();
        $poiavilis[0]->rod = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create();
        $poiavilis[0]->spryazhenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe::create();
        $poiavilis[0]->vid = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::create();
        $poiavilis[0]->vozvratnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::create();
        $poiavilis[0]->vremya = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::create();
        $poiavilis[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Razryad\Null::create();
        /*
                / **@var Morphology\Chislo\Base * /
                public $chislo;

                / **@var Morphology\Litso\Base * /
                public $litso;

                / **@var Morphology\Naklonenie\Base * /
                public $naklonenie;

                / **@var Morphology\Perehodnost\Base * /
                public $perehodnost;

                /**@var Morphology\Rod\Base * /
                public $rod;

                / * *@var Morphology\Spryazhenie\Base * /
                public $spryazhenie;

                /**@var Morphology\Vid\Base * /
                public $vid;

            /***/

        //$oblaka[0] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $oblaka[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[0], 'text', 'облака');

        $oblaka[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::create();
        $oblaka[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

//        $oblaka[1] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $oblaka[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[1], 'text', 'облака');

        $oblaka[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $oblaka[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        //$oblaka[2] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $oblaka[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[2], 'text', 'облака');

        $oblaka[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $oblaka[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();
        /*
                          'chislo' => Morphology\Chislo\Base::class,
                          'naritcatelnost' => Morphology\Naritcatelnost\Base::class,
                          'odushevlyonnost' => Morphology\Odushevlyonnost\Base::class,
                          'padeszh' => Morphology\Padeszh\Base::class,р
                          'rod' => Morphology\Rod\Base::class,
                          'sklonenie' => Morphology\Sklonenie\Base::class,

                          */

        #  $legkie[0] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $legkie[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[0], 'text', 'легкие');

        //$legkie[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $legkie[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        #$legkie[1] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $legkie[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[1], 'text', 'легкие');

        $legkie[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        #$legkie[2] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $legkie[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[2], 'text', 'легкие');

        $legkie[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $legkie[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $legkie[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        # $legkie[3] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $legkie[3] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[3], 'text', 'легкое');

        $legkie[3]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[3]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[3]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[3]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[3]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[3]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

//        $i[0] = $this->getSafeMockLocal1(Soyuz::class);
        $i[0] = $this->getMock(Soyuz::class, ['_']);
        PHPUnitHelper::setProtectedProperty($i[0], 'text', 'и');
        /*
            /* *@var Morphology\Chislo\Base * /
            public $chislo;

            /* *@var Morphology\Forma\Base * /
            public $forma;

            /* *@var Morphology\Padeszh\Base * /
            public $padeszh;

            /* *@var Morphology\Razriad\Base * /
            public $razryad;

            /* *@var Morphology\Rod\Base * /
            public $rod;

            /* *@var Morphology\StepenSravneniia\Base * /
            public $stepen_sravneniia;

            /***/
        #  $vozdushnue[0] = $this->getSafeMockLocal1(Prilagatelnoe::class);
        $vozdushnue[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[0], 'text', 'воздушные');

        $vozdushnue[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $vozdushnue[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        //$vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::create();
        $vozdushnue[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::create();

        # $vozdushnue[1] = $this->getSafeMockLocal1(Prilagatelnoe::class);
        $vozdushnue[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[1], 'text', 'воздушные');

        $vozdushnue[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $vozdushnue[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $vozdushnue[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::create();

        # $zapiztaya[0] = $this->getSafeMockLocal1(Zapiataya::class);
        $zapiztaya[0] = $this->getMock(Zapiataya::class, ['_']);


        #$serye[0] = $this->getSafeMockLocal1(Prilagatelnoe::class);
        $serye[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[0], 'text', 'серые');

        $serye[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $serye[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::create();

        # $serye[1] = $this->getSafeMockLocal1(Prilagatelnoe::class);
        $serye[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[1], 'text', 'серые');

        $serye[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $serye[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::create();

        #    $zapiztaya[1] = $this->getSafeMockLocal1(Zapiataya::class);
        $zapiztaya[1] = $this->getMock(Zapiataya::class, ['_']);

        #     $s[0] = $this->getSafeMockLocal1(Predlog::class);
        $s[0] = $this->getMock(Predlog::class, ['_']);

        PHPUnitHelper::setProtectedProperty($s[0], 'text', 'с');

        #       $rvanymi[0] = $this->getSafeMockLocal1(Prilagatelnoe::class);
        $rvanymi[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($rvanymi[0], 'text', 'рваными');

        $rvanymi[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $rvanymi[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $rvanymi[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $rvanymi[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $rvanymi[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $rvanymi[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniia\Null::create();


        #$krayami[0] = $this->getSafeMockLocal1(Suschestvitelnoe::class);
        $krayami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($krayami[0], 'text', 'краями');

        $krayami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $krayami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $krayami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $krayami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $krayami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::create();
        $krayami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        return [
            'nad' => $nad,
            'gorami' => $gorami,
            'poiavilis' => $poiavilis,
            'oblaka' => $oblaka,
            'legkie' => $legkie,
            'i' => $i,
            'vozdushnue' => $vozdushnue,
            'zapiztaya' => $zapiztaya,
            'serye' => $serye,
            's' => $s,
            'rvanymi' => $rvanymi,
            'krayami' => $krayami,
        ];
    }

    /**
     * @return Prilagatelnoe|Glagol|Suschestvitelnoe|Zapiataya|Predlog|Soyuz | \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSafeMockLocal1()
    {
        return call_user_func_array([$this, 'getSafeMock'], func_get_args());
    }

    /**
     * @return \Aot\Text\Matrix
     */
    public function getMatrix1()
    {
        $mixed = $this->getWordsAndPunctuation1();

        $matrix = new \Aot\Text\Matrix;

        foreach ($mixed as $value) {

            if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                $matrix->appendWordsForm($value);
            }

            if ($value instanceof \Aot\RussianSyntacsis\Punctuaciya\Base) {
                $matrix->appendPunctuation($value);
            }
        }

        return $matrix;
    }

    /**
     * @return \Aot\Text\NormalizedMatrix
     */
    public function getNormalizedMatrix1()
    {
        $matrix = $this->getMatrix1();

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        return $normalized_matrix;
    }

    public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = false, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false, $callOriginalMethods = false)
    {
        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $cloneArguments, $callOriginalMethods); // TODO: Change the autogenerated stub
    }


}