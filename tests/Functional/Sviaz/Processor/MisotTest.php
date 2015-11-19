<?php
namespace AotTest\Functional\Sviaz\Processor;
/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 17.11.2015
 * Time: 14:04
 */

use MivarTest\PHPUnitHelper;

class MisotTest extends \AotTest\AotDataStorage
{
    public function testSeparateRules()
    {
        $rules = [
            $this->getRule1(),
            $this->getRule2()
        ];
        $memberbase = \Aot\Sviaz\Processors\Misot::create();
        /* @var \Aot\Sviaz\Rule\Base[][] $results*/
        $results = PHPUnitHelper::callProtectedMethod($memberbase, 'separateRules', [$rules]);

        $this->assertEquals($rules[0],$results[0][0]);
        $this->assertEquals($rules[1],$results[1][0]);
    }


    public function getRule1()
    {
        $main=\Aot\Sviaz\Rule\AssertedMember\Main::create();
        PHPUnitHelper::setProtectedProperty($main, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::PODLEZHACHEE);
        $depended= \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        PHPUnitHelper::setProtectedProperty($depended, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::SKAZUEMOE);
        $base = \Aot\Sviaz\Rule\Base::create($main, $depended);
        return $base;
    }

    public function getRule2()
    {
        $main=\Aot\Sviaz\Rule\AssertedMember\Main::create();
        PHPUnitHelper::setProtectedProperty($main, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::PODLEZHACHEE);
        $depended= \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        PHPUnitHelper::setProtectedProperty($depended, 'chast_predlozhenya', 1111);
        $base = \Aot\Sviaz\Rule\Base::create($main, $depended);
        return $base;
    }



}