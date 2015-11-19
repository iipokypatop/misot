<?php
namespace AotTest\Functional\Sviaz\Podchinitrelnaya;
use MivarTest\PHPUnitHelper;

/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 17.11.2015
 * Time: 17:52
 */

class FactoryTest extends \AotTest\AotDataStorage
{
    public function dataProviderBuild()
    {
        $mockSlovo = $this
            ->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        $depend = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $main_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $depended_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);

        $rule1 = \Aot\Sviaz\Rule\Base::create($main, $depend);
        $rule2 = \Aot\Sviaz\Rule\Base::create($main, $depend);
        $rule3 = \Aot\Sviaz\Rule\Base::create($main, $depend);
        $rule4 = \Aot\Sviaz\Rule\Base::create($main, $depend);

        PHPUnitHelper::setProtectedProperty($rule1, 'type_class', \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class);
        PHPUnitHelper::setProtectedProperty($rule2, 'type_class', \Aot\Sviaz\Podchinitrelnaya\Upravlenie::class);
        PHPUnitHelper::setProtectedProperty($rule3, 'type_class', \Aot\Sviaz\Podchinitrelnaya\Primikanie::class);
        PHPUnitHelper::setProtectedProperty($rule4, 'type_class', \Aot\Sviaz\Podchinitrelnaya\Base::class);

        $sequence = \Aot\Sviaz\Sequence::create();

        $result1 = \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::create(
            $main_sequence_member,
            $depended_sequence_member,
            $rule1,
            $sequence
        );

        $result2 = \Aot\Sviaz\Podchinitrelnaya\Upravlenie::create(
            $main_sequence_member,
            $depended_sequence_member,
            $rule2,
            $sequence
        );
        $result3 = \Aot\Sviaz\Podchinitrelnaya\Primikanie::create(
            $main_sequence_member,
            $depended_sequence_member,
            $rule3,
            $sequence
        );
        $result4 = \Aot\Sviaz\Podchinitrelnaya\Base::create(
            $main_sequence_member,
            $depended_sequence_member,
            $rule4,
            $sequence
        );

        return [
            [$rule1, $result1],
            [$rule2, $result2],
            [$rule3, $result3],
            [$rule4, $result4]
        ];

    }

    /**
     * @param $main_sequence_member
     * @param $depended_sequence_member
     * @param $rule
     * @param $sequence
     * @param \Aot\Sviaz\Podchinitrelnaya\Base $expected_result
     * @dataProvider dataProviderBuild
     */
    public function testBuild_Return_Correct_Object_Type($rule, $expected_result)
    {
        $mockSlovo = $this
            ->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $main_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $depended_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $sequence = \Aot\Sviaz\Sequence::create();

        $result = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $main_sequence_member,
            $depended_sequence_member,
            $rule,
            $sequence
        );
        $this->assertInstanceOf(get_class($expected_result), $result);
    }

    public function testBuild_Return_Exeption()
    {
        $mockSlovo = $this
            ->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->getMock();
        $main_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $depended_sequence_member = \Aot\Sviaz\SequenceMember\Word\Base::create($mockSlovo);
        $sequence = \Aot\Sviaz\Sequence::create();
        $main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        $depend = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $rule_exeption = \Aot\Sviaz\Rule\Base::create($main, $depend);

        PHPUnitHelper::setProtectedProperty($rule_exeption, 'type_class', 111);

        try{
            $result = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
                $main_sequence_member,
                $depended_sequence_member,
                $rule_exeption,
                $sequence
            );
            $this->fail("Не должно было сработать");
        }
        catch(\LogicException $e){
            $this->assertEquals("unsupported Sviaz class 111", $e->getMessage());
        }

    }
}