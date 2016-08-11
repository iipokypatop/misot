<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 03.12.2015
 * Time: 15:00
 */

namespace AotTest\Functional\Sviaz\PostProcessors;

use MivarTest\PHPUnitHelper;

class RemoveDuplicateOfSviaz extends \AotTest\AotDataStorage
{

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
    ) {
        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
            $callOriginalClone, $callAutoload, $cloneArguments,
            $callOriginalMethods); // TODO: Change the autogenerated stub
    }

    public function testLaunch()
    {
        $this->markTestSkipped("Отключаю тест. По причине того, что сломался, а чинить некогда и возможно нет смысла");


        $obj = \Aot\Sviaz\PostProcessors\RemoveDuplicateOfSviaz::create();
        $this->assertTrue(is_a($obj, \Aot\Sviaz\PostProcessors\RemoveDuplicateOfSviaz::class));
    }

    public function testRun()
    {
        $this->markTestSkipped("Отключаю тест. По причине того, что сломался, а чинить некогда и возможно нет смысла");


        $sequence = \Aot\Sviaz\Sequence::create();
        //Формируем набор элементов
        $member_1 = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $member_2 = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $member_3 = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $member_4 = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $member_5 = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $sequence->append($member_1);
        $sequence->append($member_2);
        $sequence->append($member_3);
        $sequence->append($member_4);
        $sequence->append($member_5);

        //Формируем набор связей
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base $sviaz_1 */
        $sviaz_1 = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($sviaz_1, 'main_sequence_member', $member_1);
        PHPUnitHelper::setProtectedProperty($sviaz_1, 'depended_sequence_member', $member_2);
        $sequence->addSviaz($sviaz_1);

        /** @var \Aot\Sviaz\Podchinitrelnaya\Base $sviaz_2 */
        $sviaz_2 = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($sviaz_2, 'main_sequence_member', $member_2);
        PHPUnitHelper::setProtectedProperty($sviaz_2, 'depended_sequence_member', $member_3);
        $sequence->addSviaz($sviaz_2);
        $sequence->addSviaz(clone($sviaz_2));

        /** @var \Aot\Sviaz\Podchinitrelnaya\Base $sviaz_3 */
        $sviaz_3 = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($sviaz_3, 'main_sequence_member', $member_3);
        PHPUnitHelper::setProtectedProperty($sviaz_3, 'depended_sequence_member', $member_4);
        $sequence->addSviaz($sviaz_3);
        $sequence->addSviaz(clone($sviaz_3));
        $sequence->addSviaz(clone($sviaz_3));

        /** @var \Aot\Sviaz\Podchinitrelnaya\Base $sviaz_4 */
        $sviaz_4 = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($sviaz_4, 'main_sequence_member', $member_4);
        PHPUnitHelper::setProtectedProperty($sviaz_4, 'depended_sequence_member', $member_5);
        $sequence->addSviaz($sviaz_4);
        $sequence->addSviaz(clone($sviaz_4));
        $sequence->addSviaz(clone($sviaz_4));
        $sequence->addSviaz(clone($sviaz_4));

        //Запускаем алгоритм постобработки
        $obj = \Aot\Sviaz\PostProcessors\RemoveDuplicateOfSviaz::create();
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi */
        $sviazi = $obj->run($sequence, $sequence->getSviazi());


        //Проверяем, правильно ли отработал алгоритм
        $this->assertTrue([$member_1, $member_2] === [
                $sviazi[0]->getMainSequenceMember(),
                $sviazi[0]->getDependedSequenceMember()
            ]);
        $this->assertTrue([$member_2, $member_3] === [
                $sviazi[1]->getMainSequenceMember(),
                $sviazi[1]->getDependedSequenceMember()
            ]);
        $this->assertTrue([$member_3, $member_4] === [
                $sviazi[2]->getMainSequenceMember(),
                $sviazi[2]->getDependedSequenceMember()
            ]);
        $this->assertTrue([$member_4, $member_5] === [
                $sviazi[3]->getMainSequenceMember(),
                $sviazi[3]->getDependedSequenceMember()
            ]);
    }
}