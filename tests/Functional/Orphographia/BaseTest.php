<?php
/**
 * User: Яковенко Иван
 */

namespace AotTest\Functional\Orphography;

use MivarTest\PHPUnitHelper;

class BaseTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $base = \Aot\Orphography\Base::create();
        $text = "привет,приветт, приветтт, приве друг! Как дила? О чом ты думаишь? Я МорфикМорфикМорфик!КастомКастомКастом";
        $base = $base->run($text);
        $i = 0;
    }



}