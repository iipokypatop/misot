<?php

use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class ProcessorAotTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $sequence = $this->getRawSequence();
        $predlog = \Aot\Sviaz\PreProcessors\Predlog::create();
        /** @var \Aot\Sviaz\Sequence $sequence */
        $sequence = $predlog->run($sequence);

        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
        $new_sequence = $misot_to_aot->run($sequence, []);
//        \Doctrine\Common\Util\Debug::dump($new_sequence, 4);
    }

}