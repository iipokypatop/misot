<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 19:41
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\Chislitelnoe;

use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Factory;
use AotTest\AotDataStorage;
use MivarTest\PHPUnitHelper;
use Aot\MivarTextSemantic\MorphAttribute;

class HelperTest extends AotDataStorage
{

    public function testConvertToDigital()
    {
        $string = 'семьсот сорок четыре';
        $digital = \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Helper::convertToDigital($string);
        $this->assertEquals(744, $digital);
    }

    public function testConvertToString()
    {
        $this->markTestSkipped("Функционал ещё не реализован");
        $digital = '744';
        $string = \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Helper::convertToString($digital);
        $this->assertEquals(744, $string);
    }
}