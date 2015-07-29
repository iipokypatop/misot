<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 27.07.2015
 * Time: 14:52
 */

namespace AotTest\Sviaz\Preprocessors;


use MivarTest\PHPUnitHelper;

class PredlogTest extends \AotTest\AotDataStorage
{

    public function testRun()
    {
        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();


        $raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();

        $raw_sequences = $raw_member_builder->getRawSequences(
            $this->getNormalizedMatrix()
        );

        /** @var \Aot\Sviaz\SequenceMember\Word\Base[] | \Aot\Sviaz\SequenceMember\Base[] $raw_sequence */
        $raw_sequence = $raw_sequences[0];

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[0]);
        $this->assertEquals('если', PHPUnitHelper::getProtectedProperty($raw_sequence[0]->getSlovo(), 'text'));


        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[1]);
        $this->assertEquals('повышение', PHPUnitHelper::getProtectedProperty($raw_sequence[1]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[2]);
        $this->assertEquals('тарифов', PHPUnitHelper::getProtectedProperty($raw_sequence[2]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Punctuation::class, $raw_sequence[3]);


        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[4]);
        $this->assertEquals('на', PHPUnitHelper::getProtectedProperty($raw_sequence[4]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[5]);
        $this->assertEquals('электроэнергию', PHPUnitHelper::getProtectedProperty($raw_sequence[5]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[6]);
        $this->assertEquals('не', PHPUnitHelper::getProtectedProperty($raw_sequence[6]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[7]);
        $this->assertEquals('будет', PHPUnitHelper::getProtectedProperty($raw_sequence[7]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $raw_sequence[8]);
        $this->assertEquals('отменено', PHPUnitHelper::getProtectedProperty($raw_sequence[8]->getSlovo(), 'text'));

        $this->assertEquals(9, count($raw_sequence));


        $sequence = $predlog->run($raw_sequences[0]);


        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[0]);
        $this->assertEquals('если', PHPUnitHelper::getProtectedProperty($sequence[0]->getSlovo(), 'text'));


        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[1]);
        $this->assertEquals('повышение', PHPUnitHelper::getProtectedProperty($sequence[1]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[2]);
        $this->assertEquals('тарифов', PHPUnitHelper::getProtectedProperty($sequence[2]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Punctuation::class, $sequence[3]);

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\WordWithPreposition::class, $sequence[4]);
        $this->assertEquals('на', PHPUnitHelper::getProtectedProperty($sequence[4]->getPredlog(), 'text'));
        $this->assertEquals('электроэнергию', PHPUnitHelper::getProtectedProperty($sequence[4]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[5]);
        $this->assertEquals('не', PHPUnitHelper::getProtectedProperty($sequence[5]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[6]);
        $this->assertEquals('будет', PHPUnitHelper::getProtectedProperty($sequence[6]->getSlovo(), 'text'));

        $this->assertInstanceOf(\Aot\Sviaz\SequenceMember\Word\Base::class, $sequence[7]);
        $this->assertEquals('отменено', PHPUnitHelper::getProtectedProperty($sequence[7]->getSlovo(), 'text'));

        $this->assertEquals(8, count($sequence));
    }
}
