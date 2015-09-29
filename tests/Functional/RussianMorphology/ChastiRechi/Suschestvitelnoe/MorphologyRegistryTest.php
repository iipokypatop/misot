<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 19.06.2015
 * Time: 14:11
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Suschestvitelnoe;


use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use MorphAttribute;

class FactoryTest extends AotDataStorage
{
    public function testGetChastiRechiWithPriznakiWithVarianti()
    {
        print_r(\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getChastiRechiWithPriznakiWithVarianti());
    }


    public function testGetGetClasses()
    {
        print_r(\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getClasses());
    }

}