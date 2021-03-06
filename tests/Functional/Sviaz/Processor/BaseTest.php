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

use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use MivarTest\PHPUnitHelper;

use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class BaseTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $processor = \Aot\Sviaz\Processor::create();

        $rule = $this->getRule1();
        $rule = $this->getRule2();

        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule]
        );


        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }

        $result = array_filter($sviazi_container);

        $pretty = $this->pretty(
            $result
        );

        //echo join("\n", $pretty);

    }

    protected function getRule2()
    {

        $builder = \Aot\Sviaz\Rule\Builder::create()
            ->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE)
            ->mainCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->mainMorphology(MorphologyRegistry::CHISLO_EDINSTVENNOE)
            ->mainMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->mainMorphology(MorphologyRegistry::ROD_SREDNIJ)
            ->mainRole(RoleRegistry::SVOISTVO)
            ->dependedChastRechi(ChastiRechiRegistry::PRILAGATELNOE)
            ->dependedCheck(MemberCheckerRegistry::PredlogPeredSlovom)
            ->dependedMorphology(MorphologyRegistry::PADESZH_IMENITELNIJ)
            ->dependedMorphology(MorphologyRegistry::ROD_MUZHSKOI)
            ->dependedRole(RoleRegistry::OTNOSHENIE);

        // $builder->s

        $builder->dependedAndMainMorphologyMatching(
            MorphologyRegistry::PADESZH
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
//            new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::class
        );

        $asserted_main->setRoleClass(
            \Aot\Sviaz\Role\Vesch::class
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
//            new \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::class
        );

        $asserted_depended->setRoleClass(
            \Aot\Sviaz\Role\Svoistvo::class
        );

        return $asserted_depended;
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


        // падеж
        $asserted_matching[0] = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
            SuschestvitelnoePadeszhBase::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class
        );

        // род
        $asserted_matching[1] = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base::class
        );

        // число
        $asserted_matching[2] = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
            Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base::class
        );



        $rule->addAssertedMatching($asserted_matching[0]);
        $rule->addAssertedMatching($asserted_matching[1]);
        $rule->addAssertedMatching($asserted_matching[2]);


        $rule->addChecker(
            \Aot\Sviaz\Rule\Checker\Registry::getObjectById(
                \Aot\Sviaz\Rule\Checker\Registry::NetSuschestvitelnogoVImenitelnomPadeszhe
            )
        );


        return $rule;
    }


    public function testFirst()
    {
        //$this->markTestSkipped();

        $processor = \Aot\Sviaz\Processor::create();

        $rule = $this->getRule1();


        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule]
        );

        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }


        $pretty = $this->pretty(
            $sviazi_container
        );

        //echo join("\n", $pretty);
    }

    public function testSecond()
    {

        $processor = \Aot\Sviaz\Processor::create();


        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            array_merge([
                \Aot\Sviaz\Rule\Container::getRule1(),
                \Aot\Sviaz\Rule\Container::getRule2(),

                \Aot\Sviaz\Rule\Container::getRule4(),
                \Aot\Sviaz\Rule\Container::getRule5(),
                //\Aot\Sviaz\Rule\Container::getRuleSuchestvitelnoeiSuchestvitelnoeDocOtLarisu(),
            ], \Aot\Sviaz\Rule\Container::getRuleSuch1()
            )
        );



        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }

        $pretty = $this->pretty(
            $sviazi_container
        );

        //echo join("\n", $pretty);
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

        $gorami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($gorami[0], 'text', 'горами');

        $gorami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $gorami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $gorami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $gorami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $gorami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::create();
        $gorami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $poiavilis[0] = $this->getMock(Glagol::class, ['_']);
        PHPUnitHelper::setProtectedProperty($poiavilis[0], 'text', 'появились');
        $poiavilis[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::create();
        $poiavilis[0]->litso = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::create();
        $poiavilis[0]->naklonenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe::create();
        $poiavilis[0]->perehodnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::create();
        $poiavilis[0]->rod = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\ClassNull::create();
        $poiavilis[0]->spryazhenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe::create();
        $poiavilis[0]->vid = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::create();
        $poiavilis[0]->vozvratnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::create();
        $poiavilis[0]->vremya = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::create();
        $poiavilis[0]->zalog = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\ClassNull::create();

        $oblaka[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[0], 'text', 'облака');
        $oblaka[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::create();
        $oblaka[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $oblaka[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[1], 'text', 'облака');
        $oblaka[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $oblaka[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $oblaka[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[2], 'text', 'облака');
        $oblaka[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $oblaka[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();


        $legkie[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[0], 'text', 'легкие');
        $legkie[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $legkie[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[1], 'text', 'легкие');
        $legkie[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $legkie[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[2], 'text', 'легкие');
        $legkie[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $legkie[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $legkie[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $legkie[3] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[3], 'text', 'легкое');
        $legkie[3]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[3]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[3]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[3]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[3]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[3]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

        $i[0] = $this->getMock(Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($i[0], 'text', 'и');


        $vozdushnue[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[0], 'text', 'воздушные');
        $vozdushnue[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $vozdushnue[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::create();
        //$vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::create();
        $vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::create();
        $vozdushnue[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::create();

        $vozdushnue[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[1], 'text', 'воздушные');
        $vozdushnue[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $vozdushnue[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::create();
        $vozdushnue[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::create();
        $vozdushnue[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::create();

        $zapiztaya[0] = $this->getMock(Zapiataya::class, ['_']);

        $serye[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[0], 'text', 'серые');

        $serye[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $serye[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::create();
        $serye[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::create();
        $serye[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::create();

        $serye[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[1], 'text', 'серые');
        $serye[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $serye[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::create();
        $serye[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::create();
        $serye[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::create();

        $zapiztaya[1] = $this->getMock(Zapiataya::class, ['_']);

        #     $s[0] = $this->getSafeMockLocal1(Predlog::class);
        $s[0] = $this->getMock(Predlog::class, ['_']);
        PHPUnitHelper::setProtectedProperty($s[0], 'text', 'с');

        $rvanymi[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($rvanymi[0], 'text', 'рваными');
        $rvanymi[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $rvanymi[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $rvanymi[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $rvanymi[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\ClassNull::create();
        $rvanymi[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\ClassNull::create();
        $rvanymi[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\ClassNull::create();


        $krayami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($krayami[0], 'text', 'краями');
        $krayami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $krayami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $krayami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $krayami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $krayami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::create();
        $krayami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\ClassNull::create();

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
     * @return Prilagatelnoe|Glagol|Suschestvitelnoe|Zapiataya|Predlog|Base | \PHPUnit_Framework_MockObject_MockObject
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

        $matrix = \Aot\Text\Matrix::create($mixed);

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

    /**
     * @param string $originalClassName
     * @param array $methods
     * @param array $arguments
     * @param string $mockClassName
     * @param bool|false $callOriginalConstructor
     * @param bool|true $callOriginalClone
     * @param bool|true $callAutoload
     * @param bool|false $cloneArguments
     * @param bool|false $callOriginalMethods
     * @return Prilagatelnoe|Glagol|Suschestvitelnoe|Zapiataya|Predlog|Base | \PHPUnit_Framework_MockObject_MockObject
     */

    public function getMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = false,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false,
        $proxyTarget = null
    )
    {
        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload, $cloneArguments, $callOriginalMethods); // TODO: Change the autogenerated stub
    }

}