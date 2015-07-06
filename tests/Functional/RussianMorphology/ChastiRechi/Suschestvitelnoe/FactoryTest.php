<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 19.06.2015
 * Time: 14:11
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Suschestvitelnoe;


use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Factory;
use MivarTest\Base;

class FactoryTest extends \AotTest\AotDataStorage
{
    public function testbuild()
    {
        $text = 'мама';

        #$result = Factory::get()->build($text);

        #var_export($result);die;
    }
}