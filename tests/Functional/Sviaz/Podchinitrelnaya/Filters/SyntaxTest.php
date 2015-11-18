<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq;
use MivarTest\PHPUnitHelper;


class SyntaxTest extends \AotTest\AotDataStorage
{
    /**
     * /brief Тест создания фильтра (и создание последовательностей)
     */
    public function testLaunch()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $sequences = $this->getSequencesForTests();
        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        //$this->printSviazi($sequences[16]);
    }

    /**
     * \brief Когда фильтр отработал (обратный порядок слов)
     */
    public function testCase1()
    {
        $this->markTestSkipped("");

        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();

        //Конфликтующий набор связей
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $conflicting_group_of_sviazey */
        $conflicting_group_of_sviazey = $this->getNaborSviazeyForTestCase1();
        //$this->printSviazi($res);//Печать связей

        $sviazi_after_filter = $filter_syntax->run($conflicting_group_of_sviazey);
        //$this->printSviazi($sviazi_after_filter); //Печать связей

        $this->assertEquals([['облака', 'воздушные']], $this->getProstoyMassivSviazi($sviazi_after_filter));

        //print_r($this->getProstoyMassivSviazi($conflicting_group_of_sviazey));
        //print_r($this->getProstoyMassivSviazi($sviazi_after_filter));
    }

    public function getNaborSviazeyForTestCase1()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $number_sequence = 16;
        $sequence = $this->getSequencesForTests()[$number_sequence];
        $number_sviaz1 = 3;
        $number_sviaz2 = 0;
        $number_sviaz3 = 0;
        $number_sviaz4 = 7;
        return [
            $sequence[$number_sviaz1],
            $sequence[$number_sviaz2],
            $sequence[$number_sviaz3],
            $sequence[$number_sviaz4]
        ];
    }


    /**
     * \brief Когда чего-то нет в БД и фильтр не должен отработать
     */
    public function testCase2()
    {
        $this->markTestSkipped("");

        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $conflicting_group_of_sviazey */
        //Конфликтующий набор связей
        $conflicting_group_of_sviazey = $this->getNaborSviazeyForTestCase2();
        //print_r($this->getProstoyMassivSviazi($conflicting_group_of_sviazey));
        $sviazi_after_filter = $filter_syntax->run($conflicting_group_of_sviazey);
        //print_r($this->getProstoyMassivSviazi($sviazi_after_filter));

        $this->assertEquals([['серые', 'горами'], ['серые', 'облака'], ['серые', 'легкие']],
            $this->getProstoyMassivSviazi($sviazi_after_filter));
    }

    public function getNaborSviazeyForTestCase2()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $number_sequence = 0;
        $sequence = $this->getSequencesForTests()[$number_sequence];
        $number_sviaz1 = 5;
        $number_sviaz2 = 6;
        $number_sviaz3 = 7;
        return [
            $sequence[$number_sviaz1],
            $sequence[$number_sviaz2],
            $sequence[$number_sviaz3]
        ];
    }

    /**
     * \brief Когда подаётся только одна связь
     */
    public function testCase3()
    {
        $this->markTestSkipped("");

        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $conflicting_group_of_sviazey */
        //Конфликтующий набор связей
        $conflicting_group_of_sviazey = $this->getNaborSviazeyForTestCase3();
        //print_r($this->getProstoyMassivSviazi($conflicting_group_of_sviazey));
        $sviazi_after_filter = $filter_syntax->run($conflicting_group_of_sviazey);
        //print_r($this->getProstoyMassivSviazi($sviazi_after_filter));

        $this->assertEquals([['серые', 'горами']], $this->getProstoyMassivSviazi($sviazi_after_filter));
    }

    public function getNaborSviazeyForTestCase3()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $number_sequence = 0;
        $sequence = $this->getSequencesForTests()[$number_sequence];
        $number_sviaz1 = 5;
        return [
            $sequence[$number_sviaz1]
        ];
    }

    /**
     * \brief Когда в БД найдено несколько связей
     */
    public function testCase4()
    {
        $this->markTestSkipped("");

        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $conflicting_group_of_sviazey */
        //Конфликтующий набор связей
        $conflicting_group_of_sviazey = $this->getNaborSviazeyForTestCase4();
        //print_r($this->getProstoyMassivSviazi($conflicting_group_of_sviazey));
        $sviazi_after_filter = $filter_syntax->run($conflicting_group_of_sviazey);
        //print_r($this->getProstoyMassivSviazi($sviazi_after_filter));

        $this->assertEquals([['серые', 'легкие'], ['серые', 'облака'], ['серые', 'краями']],
            $this->getProstoyMassivSviazi($sviazi_after_filter));
    }

    public function getNaborSviazeyForTestCase4()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $number_sequence = 16;
        $sequence = $this->getSequencesForTests()[$number_sequence];
        $number_sviaz1 = 8;
        $number_sviaz2 = 7;
        $number_sviaz3 = 9;
        return [
            $sequence[$number_sviaz1],
            $sequence[$number_sviaz2],
            $sequence[$number_sviaz3]
        ];
    }


    /**
     * \brief Когда фильтр отработал (прямой порядок слов)
     */
    public function testCase5()
    {
        $this->markTestSkipped("");

        //Создаём фильтр
        $filter_syntax = \Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();

        //Конфликтующий набор связей
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $conflicting_group_of_sviazey */
        $conflicting_group_of_sviazey = $this->getNaborSviazeyForTestCase5();
        //$this->printSviazi($conflicting_group_of_sviazey);//Печать связей

        $sviazi_after_filter = $filter_syntax->run($conflicting_group_of_sviazey);
        //$this->printSviazi($res2); //Печать связей

        $this->assertEquals([['облака', 'воздушные']], $this->getProstoyMassivSviazi($sviazi_after_filter));

        //print_r($this->getProstoyMassivSviazi($conflicting_group_of_sviazey));
        //print_r($this->getProstoyMassivSviazi($sviazi_after_filter));
    }

    public function getNaborSviazeyForTestCase5()
    {
        $this->markTestSkipped("");

        //создаём последовательности из тестового примера
        $number_sequence = 16;
        $sequence = $this->getSequencesForTests()[$number_sequence];
        $number_sviaz1 = 0;
        $number_sviaz2 = 3;
        $number_sviaz3 = 0;
        $number_sviaz4 = 7;
        return [
            $sequence[$number_sviaz1],
            $sequence[$number_sviaz2],
            $sequence[$number_sviaz3],
            $sequence[$number_sviaz4]
        ];
    }


    /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi */
    protected function printSviazi($sviazi)
    {
        $result = array_filter([$sviazi]);
        $pretty = $this->pretty(
            $result
        );
        echo join("\n", $pretty);
        echo "\n";

    }

    /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi */
    protected function getProstoyMassivSviazi($sviazi)
    {
        $result = [];
        foreach ($sviazi as $sviaz) {
            $result[] = [
                $sviaz->getMainSequenceMember()->getSlovo()->getText(),
                $sviaz->getDependedSequenceMember()->getSlovo()->getText()
            ];
        }
        return $result;
    }


    public function getSequencesForTests()
    {
        //СОздаём процессор
        $processor = \Aot\Sviaz\Processor::createDefault();

        //Получаем два правила, причё они будут противоречить друг другу
        $rule1 = $this->getRule1();
        $rule2 = $this->getRule2();

        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule1, $rule2]
        );

        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }

        return $sviazi_container;
    }


    public function getMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = false,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    ) {
        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
            $callOriginalClone, $callAutoload, $cloneArguments,
            $callOriginalMethods); // TODO: Change the autogenerated stub
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

    public function getMatrix1()
    {
        $mixed = $this->getWordsAndPunctuation1();

        $matrix = \Aot\Text\Matrix::create($mixed);

        return $matrix;
    }

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
        PHPUnitHelper::setProtectedProperty($gorami[0], 'initial_form', 'гора');
        $gorami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $gorami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $gorami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $gorami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $gorami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::create();
        $gorami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $poiavilis[0] = $this->getMock(Glagol::class, ['_']);
        PHPUnitHelper::setProtectedProperty($poiavilis[0], 'text', 'появились');
        PHPUnitHelper::setProtectedProperty($poiavilis[0], 'initial_form', 'появиться');
        $poiavilis[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe::create();
        $poiavilis[0]->litso = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::create();
        $poiavilis[0]->naklonenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe::create();
        $poiavilis[0]->perehodnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::create();
        $poiavilis[0]->rod = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create();
        $poiavilis[0]->spryazhenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe::create();
        $poiavilis[0]->vid = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::create();
        $poiavilis[0]->vozvratnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::create();
        $poiavilis[0]->vremya = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::create();
        $poiavilis[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::create();


        $oblaka[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[0], 'text', 'облака');
        PHPUnitHelper::setProtectedProperty($oblaka[0], 'initial_form', 'облако');
        $oblaka[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::create();
        $oblaka[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();


        $oblaka[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[1], 'text', 'облака');
        PHPUnitHelper::setProtectedProperty($oblaka[1], 'initial_form', 'облако');
        $oblaka[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $oblaka[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $oblaka[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[2], 'text', 'облака');
        PHPUnitHelper::setProtectedProperty($oblaka[2], 'initial_form', 'облако');
        $oblaka[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $oblaka[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $oblaka[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();


        $legkie[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[0], 'text', 'легкие');
        PHPUnitHelper::setProtectedProperty($legkie[0], 'initial_form', 'легкий');

        $legkie[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[1], 'text', 'легкие');
        PHPUnitHelper::setProtectedProperty($legkie[1], 'initial_form', 'легкий');
        $legkie[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[2], 'text', 'легкие');
        PHPUnitHelper::setProtectedProperty($legkie[2], 'initial_form', 'легкий');
        $legkie[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $legkie[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $legkie[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[3] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[3], 'text', 'легкое');
        PHPUnitHelper::setProtectedProperty($legkie[3], 'initial_form', 'легкий');
        $legkie[3]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[3]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[3]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[3]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[3]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[3]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $i[0] = $this->getMock(Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($i[0], 'text', 'и');


        $vozdushnue[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[0], 'text', 'воздушные');
        PHPUnitHelper::setProtectedProperty($vozdushnue[0], 'initial_form', 'воздушный');
        $vozdushnue[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $vozdushnue[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        //$vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::create();
        $vozdushnue[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $vozdushnue[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[1], 'text', 'воздушные');
        PHPUnitHelper::setProtectedProperty($vozdushnue[1], 'initial_form', 'воздушный');
        $vozdushnue[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $vozdushnue[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $vozdushnue[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $zapiztaya[0] = $this->getMock(Zapiataya::class, ['_']);

        $serye[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[0], 'text', 'серые');
        PHPUnitHelper::setProtectedProperty($serye[0], 'initial_form', 'серый');

        $serye[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $serye[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $serye[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[1], 'text', 'серые');
        PHPUnitHelper::setProtectedProperty($serye[1], 'initial_form', 'серый');
        $serye[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $serye[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();


        #     $s[0] = $this->getSafeMockLocal1(Predlog::class);
        $s[0] = $this->getMock(Predlog::class, ['_']);
        PHPUnitHelper::setProtectedProperty($s[0], 'text', 'с');

        $rvanymi[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($rvanymi[0], 'text', 'рваными');
        PHPUnitHelper::setProtectedProperty($rvanymi[0], 'initial_form', 'рваный');
        $rvanymi[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $rvanymi[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $rvanymi[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $rvanymi[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $rvanymi[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $rvanymi[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();


        $krayami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($krayami[0], 'text', 'краями');
        PHPUnitHelper::setProtectedProperty($krayami[0], 'initial_form', 'край');
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

    protected function getRule1()
    {
        <<<RULE
        Если в предложении стоят подряд существительное, а за ним – причастие, и они совпадают в роде, числе и падеже, то между ними есть связь.
RULE;
        $asserted_main = $this->get_asserted_main1();
        $asserted_depended = $this->get_asserted_depended1();

        $rule = \Aot\Sviaz\Rule\Base::create(
            $asserted_main,
            $asserted_depended
        );

        PHPUnitHelper::setProtectedProperty($rule->getDao(), 'id', "1");
        //print_r("id:".$rule->getDao()->getId());


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

    protected function getRule2()
    {
        <<<RULE
        Если в предложении стоят подряд существительное, а за ним – причастие, и они совпадают в роде, числе и падеже, то между ними есть связь.
RULE;
        $asserted_main = $this->get_asserted_main2();
        $asserted_depended = $this->get_asserted_depended2();

        $rule = \Aot\Sviaz\Rule\Base::create(
            $asserted_main,
            $asserted_depended
        );

        PHPUnitHelper::setProtectedProperty($rule->getDao(), 'id', "2");

        $rule->addChecker(
            \Aot\Sviaz\Rule\Checker\Registry::getObjectById(
                \Aot\Sviaz\Rule\Checker\Registry::NetSuschestvitelnogoVImenitelnomPadeszhe
            )
        );


        return $rule;
    }


    protected function get_asserted_main1()
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
    protected function get_asserted_depended1()
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

    protected function get_asserted_main2()
    {
        $asserted_main = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $asserted_main->assertChastRechi(
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class
        );


        $asserted_main->setRoleClass(
            \Aot\Sviaz\Role\Svoistvo::class
        );

        return $asserted_main;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Depended
     */
    protected function get_asserted_depended2()
    {
        $asserted_depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();


        $asserted_depended->assertChastRechi(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class
        );


        $asserted_depended->setRoleClass(
            \Aot\Sviaz\Role\Vesch::class
        );

        return $asserted_depended;
    }

}