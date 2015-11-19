<?php
namespace AotTest\Functional\Sviaz\Podchinitrelnaya;

use MivarTest\PHPUnitHelper;

/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 17.11.2015
 * Time: 12:26
 */
class BaseTest extends \AotTest\AotDataStorage
{
    public function testCreate()
    {
        $mockSlovo = $this
            ->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mainRule = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        $dependRule = \Aot\Sviaz\Rule\AssertedMember\Depended::create();

        $main_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $depended_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $rule = \Aot\Sviaz\Rule\Base::create($mainRule, $dependRule);
        $sequence = \Aot\Sviaz\Sequence::create();

        $memberBase = \Aot\Sviaz\Podchinitrelnaya\Base::create(
            $main_sequence_member,
            $depended_sequence_member,
            $rule,
            $sequence
        );

        $this->assertEquals($main_sequence_member, $memberBase->getMainSequenceMember());
        $this->assertEquals($depended_sequence_member, $memberBase->getDependedSequenceMember());
        $this->assertEquals($rule, $memberBase->getRule());
        $this->assertEquals($sequence, $memberBase->getSequence());
        $this->assertEquals(spl_object_hash($memberBase), $memberBase->getId());
    }
}