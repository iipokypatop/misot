<?php

use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class ProcessorAotTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $misot_to_aot = \Aot\Sviaz\Processors\Aot\Base::create();
    }


    public function testGetNewSequence()
    {
        $sequence = $this->getRawSequence();

        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();

        /** @var \Aot\Sviaz\Sequence $sequence */
        $sequence = $predlog->run($sequence);

        $misot_to_aot = \Aot\Sviaz\Processors\Aot\Base::create();
        $new_sequence = $misot_to_aot->run($sequence, []);

        $this->assertNotEmpty($new_sequence->getSviazi());

        // проверяем наличие всех мемберов из связей в последовательности
        foreach ($new_sequence->getSviazi() as $sviaz) {
            $pos = $new_sequence->getPosition($sviaz->getMainSequenceMember());
            $pos2 = $new_sequence->getPosition($sviaz->getDependedSequenceMember());
            $this->assertNotNull($pos);
            $this->assertNotNull($pos2);
        }
    }

    /**
     * Проверяем на корректность связи между "не" и "лес"
     */
    public function testLinkSuschestvitelnoeWithChasticaNe()
    {
        $text = 'Я пошел не в лес';
        $seq_converter = \Aot\Sviaz\CreateSequenceFromText::create();
        $seq_converter->convert($text);
        $sequence = $seq_converter->getSequences()[0];

        $misot_to_aot = \Aot\Sviaz\Processors\Aot\Base::create();
        $new_sequence = $misot_to_aot->run($sequence, []);

        $this->assertNotEmpty($new_sequence->getSviazi());
        $was_link_suschestvitelnoe_and_ne = false;
        // проверяем наличие всех мемберов из связей в последовательности
        foreach ($new_sequence->getSviazi() as $sviaz) {
            if ($sviaz->getMainSequenceMember()->getSlovo()->getText() === 'лес'
                &&
                $sviaz->getDependedSequenceMember()->getSlovo()->getText() === 'не'
            ) {

                $this->assertInstanceOf(
                    \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::class,
                    $sviaz->getMainSequenceMember()
                );

                $this->assertInstanceOf(
                    \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class,
                    $sviaz->getMainSequenceMember()->getSlovo()
                );

                $this->assertInstanceOf(
                    \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class,
                    $sviaz->getMainSequenceMember()->getPredlog()
                );

                $this->assertInstanceOf(
                    \Aot\RussianMorphology\ChastiRechi\Chastica\Base::class,
                    $sviaz->getDependedSequenceMember()->getSlovo()
                );

                $was_link_suschestvitelnoe_and_ne = true;
            }
        }
        $this->assertEquals(true, $was_link_suschestvitelnoe_and_ne);
    }


    /**
     * Прогоняем предложения и смотрим, что все мемберы из связей совпадают с мемберами из последовательности
     * @dataProvider dataProviderSentences
     * @param $sentence
     */
    public function testRelationsInSentencesBuildedCorrectly($sentence)
    {
        $seq_converter = \Aot\Sviaz\CreateSequenceFromText::create();
        $seq_converter->convert($sentence);
        $sequence = $seq_converter->getSequences()[0];

//        die();
        /**
         * TODO: переделать получение последовательности
         * 1 - slova
         * 2 - создать матрицу
         * 3 - получить нормализованную матрицу
         * 4 - Matrix->getCopyWithOnlyOneRaw...()
         */

        $misot_to_aot = \Aot\Sviaz\Processors\Aot\Base::create();
        $new_sequence = $misot_to_aot->run($sequence, []);

        $this->assertNotEmpty($new_sequence->getSviazi());

        // сравниваем исходное предложение с восстановленным из полученной последовательности
        $sentence_array = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
//                $sentence_array[] = $member->getPunctuaciya()->getText();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition) {
                /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                $sentence_array[] = $member->getPredlog()->getText();
                $sentence_array[] = $member->getSlovo()->getText();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
                $sentence_array[] = $member->getSlovo()->getText();
            }
        }

        // проверяем совпадение исходного предложения с восстановленным из новой последовательности
        $sentence = mb_strtolower($sentence, 'utf-8');
        $sentence = preg_replace("/[\\,\\.\\-]/u", "", $sentence);
        $sentence = preg_replace("/\\s{2,}/u", " ", $sentence);
        $this->assertEquals($sentence, join(" ", $sentence_array));

        // проверяем наличие всех мемберов из связей в последовательности
        foreach ($new_sequence->getSviazi() as $sviaz) {
            $pos = $new_sequence->getPosition($sviaz->getMainSequenceMember());
            $pos2 = $new_sequence->getPosition($sviaz->getDependedSequenceMember());
            $this->assertNotNull($pos);
            $this->assertNotNull($pos2);
        }
    }


    /**
     * @return array
     */
    public function dataProviderSentences()
    {
        return [
//            ['Алиса-каприза пошла в магазин-намазин'],// epic fail! (Aot -X-> Misot)
//            ['Я выбрал приятное кафе - уютное, чистое и теплое.'],// epic fail! (Aot -X-> Misot)
            ['Я посмотрел на нее'],
            ['Мальчик пошел в лес.'],
//            ['Ее черные волосы, как вороново крыло, закрывали часть щеки.'],// lagging
//            ['Человек пойдет в лес, если дома не будет еды.'], // lagging
//            ['Папа, мама и брат пойдут в лес, если дома не будет еды.'], // lagging
            ['Дровосек пошел в лес рубить дрова.'],
            ['Папа сжег в лес, в котором рубил дрова.'], // предложение с опечаткой
            ['Папа, мама и бабушка пошли в магазин.'],
            ['Василий Петрович купил сигареты.'],
            ['Василий, Петрович купил сигареты.'],
        ];
    }

}