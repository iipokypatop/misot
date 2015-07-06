<?php

namespace AotTest;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 0:03
 */
class AotDataStorageTest extends AotDataStorage
{
    public function testLaunch()
    {
        $this->getWordsAndPunctuation();
    }
}