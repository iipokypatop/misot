<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


use MivarTest\PHPUnitHelper;

class BuildTermTreeFromTextTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $text = "Толстуха собралась стирать пижаму. Папа пропал, цепь привлекла глаз. Полка.";
        $text = "Мальчик пошёл охотиться и взял с собой лук";
        $res = \Aot\Tools\BuildTermTreeFromText::run($text);
        //print_r($res);
    }

    public function testInitialFormAlreadyMet()
    {
        $slova = [];
        $initial_forms = [
            'test_1',
            'test_2',
            'test_2',
            'test_3',
            'test_4',
            'test_4',
            'test_5',
        ];
        $expected_results = [
            false,
            false,
            true,
            false,
            false,
            true,
            false,
        ];

        for ($i = 0; $i <= 6; $i++) {
            $slovo = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
                ->disableOriginalConstructor()
                ->setMethods(['_'])
                ->getMock();
            PHPUnitHelper::setProtectedProperty($slovo, 'initial_form', $initial_forms[$i]);
            $slova[] = $slovo;
        }


        $tested_class = $this->getMockBuilder(\Aot\Tools\BuildTermTreeFromText::class)
            ->disableOriginalConstructor()
            ->getMock();

        $actual_results = [];
        foreach ($initial_forms as $index => $initial_form) {
            $actual_results[] = PHPUnitHelper::callProtectedMethod(
                $tested_class,
                'initialFormAlreadyMet',
                [$slova, $index, $initial_form]
            );
        }

        $this->assertEquals($expected_results, $actual_results);

    }
}