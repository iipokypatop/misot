<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:47
 */

namespace AotTest\Functional\Sviaz\Processor;

class BaseTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $processor = \Aot\Sviaz\Processor\Base::create();

        $rule = $this->getRule();

        $processor->go(
            $this->getNormalizedMatrix(),
            [$rule]
        );
    }
}