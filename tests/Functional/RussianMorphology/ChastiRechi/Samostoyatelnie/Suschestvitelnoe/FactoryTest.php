<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 19.06.2015
 * Time: 14:11
 */

namespace MivarTest\Functional\RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe;


use MivarTest\Base;
use RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe\Factory;

class FactoryTest extends Base
{

    public function testbuild()
    {
        $text = 'мама';

        $result = Factory::get()->build($text);

        var_export($result);die;
    }
}