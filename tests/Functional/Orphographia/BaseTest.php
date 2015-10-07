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
        $text = "Пивет, малоко! How do you do? Есть новость. Рассказать? Bye!";
        (($base->run($text)));
    }



}