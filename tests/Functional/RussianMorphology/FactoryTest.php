<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;


class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $words = preg_split('/\s+/', <<<TEXT
Большая ываываывааыва пять часть средств ушла на десять приобретение картины итальянского художника
TEXT
        );

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
    }
}