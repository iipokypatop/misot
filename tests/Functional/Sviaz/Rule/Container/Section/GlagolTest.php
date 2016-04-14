<?php

namespace AotTest\Functional\Sviaz\Rule\Container\Section;

/**
 * Created by PhpStorm.
 * User: Peter Semenyuk
 * Date: 014, 14, 04, 2016
 * Time: 12:06
 */
class GlagolTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $glagol = \Aot\Sviaz\Rule\Container\Section\Glagol::create();
    }
}