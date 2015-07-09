<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 14:27
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Prichastie;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Factory;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $factory = Factory::get();
    }
    public function testBuild()
    {
        $dw = new \Dw(); // + добавить параметры
        $word = new \Word(); // + добавить параметры
        $factory = Factory::get()->build($dw, $word);
    }

}