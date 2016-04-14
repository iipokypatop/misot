<?php

namespace AotTest\Functional\Sviaz\Rule\Container;

/**
 * Created by PhpStorm.
 * User: Peter Semenyuk
 * Date: 014, 14, 04, 2016
 * Time: 12:07
 */
class RuleListTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $rules = \Aot\Sviaz\Rule\Container\RulesList::getRules();

    }
} 