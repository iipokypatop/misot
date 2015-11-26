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
//        \Doctrine\Common\Util\Debug::dump($new_sequence, 4);
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
        $sequence = $seq_converter->getSequence()[0];

//        die();
//        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();
//        $sequence = $predlog->run($sequence);

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
                // пропускаем, поскольку АОТ игнорирует знаки препинания
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
        $sentence = preg_replace("/[\\,\\.]/u", "",$sentence);
        $this->assertEquals($sentence, join(" ", $sentence_array));

        // проверяем наличие всех мемберов из связей в последовательности
        foreach ($new_sequence->getSviazi() as $sviaz) {
            $pos = $new_sequence->getPosition($sviaz->getMainSequenceMember());
            $pos2 = $new_sequence->getPosition($sviaz->getDependedSequenceMember());
            $this->assertNotNull($pos);
            $this->assertNotNull($pos2);
        }
//        \Doctrine\Common\Util\Debug::dump($new_sequence, 5);
    }


    /**
     * @return array
     */
    public function dataProviderSentences()
    {
        return [
            ['Мальчик пошел в лес.'],
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