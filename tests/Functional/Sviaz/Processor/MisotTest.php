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
        $processors_misot = \Aot\Sviaz\Processors\Misot::create();
        /* @var \Aot\Sviaz\Rule\Base[][] $results */
        $results = PHPUnitHelper::callProtectedMethod($processors_misot, 'separateRules', [$rules]);
        $this->assertTrue(isset($results[0][0]));
        $this->assertEquals($rules[0], $results[0][0]);
        $this->assertTrue(isset($results[1][0]));
        $this->assertEquals($rules[1], $results[1][0]);
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule1()
    {
        $main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        PHPUnitHelper::setProtectedProperty($main, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::PODLEZHACHEE);
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        PHPUnitHelper::setProtectedProperty($depended, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::SKAZUEMOE);
        $base = \Aot\Sviaz\Rule\Base::create($main, $depended);
        return $base;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule2()
    {
        $main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
        PHPUnitHelper::setProtectedProperty($main, 'chast_predlozhenya', \Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry::PODLEZHACHEE);
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        PHPUnitHelper::setProtectedProperty($depended, 'chast_predlozhenya', 1111);
        $base = \Aot\Sviaz\Rule\Base::create($main, $depended);
        return $base;
    }
}