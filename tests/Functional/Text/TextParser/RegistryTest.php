<?php
namespace AotTest\Functional\Text\TextParser;

use MivarTest\PHPUnitHelper;

/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 12.11.2015
 * Time: 13:35
 */
class RegistryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        \Aot\Text\TextParser\TextParser::create();
    }

    /**
     * @brief провайдер для тестирования метода \Aot\Text\TextParser\Registry::get
     * @see \Aot\Text\TextParser\Registry::get
     */
    public function dataProviderGet()
    {
        return [
            [111, [111 => 'any value']],
            [222, [2222 => 'no key 222']]
        ];
    }

    /**
     * @brief тестирование метода \Aot\Text\TextParser\Registry::get
     * @param int $index
     * @param array $expected_result
     * @dataProvider dataProviderGet
     */
    public function testGet($index, array $expected_result)
    {
        assert(is_int($index));
        $registry = \Aot\Text\TextParser\Registry::create();
        PHPUnitHelper::setProtectedProperty($registry, 'registry', $expected_result);
        if ($index == 111) {
            $this->assertEquals($expected_result[$index], $registry->get($index));
        } elseif ($index == 222) {
            $this->assertEquals(false, $registry->get($index));
        }
    }
}