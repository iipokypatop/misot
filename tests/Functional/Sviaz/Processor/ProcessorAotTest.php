<?php

use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class ProcessorAotTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
    }


    public function testGetNewSequence()
    {
        $sequence = $this->getRawSequence();

        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();

        /** @var \Aot\Sviaz\Sequence $sequence */
        $sequence = $predlog->run($sequence);

        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
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
     *
     * @dataProvider dataProviderSentences
     * @param $sentence
     */
    public function testAAA($sentence)
    {
        $seq_converter = \Aot\Sviaz\CreateSequenceFromText::create();
        $seq_converter->convert($sentence);
        $sequence = $seq_converter->getSequence()[0];
        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();
        /** @var \Aot\Sviaz\Sequence $sequence */
        $sequence = $predlog->run($sequence);


        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
        $new_sequence = $misot_to_aot->run($sequence, []);

        $this->assertNotEmpty($new_sequence->getSviazi());

        // проверяем наличие всех мемберов из связей в последовательности
        foreach ($new_sequence->getSviazi() as $sviaz) {
            $pos = $new_sequence->getPosition($sviaz->getMainSequenceMember());
            $pos2 = $new_sequence->getPosition($sviaz->getDependedSequenceMember());
            $this->assertNotNull($pos);
            $this->assertNotNull($pos2);
        }
//        \Doctrine\Common\Util\Debug::dump($new_sequence, 5);
    }



    public function dataProviderSentences()
    {
        return [
            ['Мальчик пошел в лес.'],
            ['Дровосек пошел в лес рубить дрова.'],
            ['Папа сжег в лес, в котором рубил дрова.'], // #TODO: предложение с опечаткой, отлавливать
            ['Папа, мама и бабушка пошли в магазин.'],
            ['Василий, Петрович купил сигареты.'],
        ];
    }

}