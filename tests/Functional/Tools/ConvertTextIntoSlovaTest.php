<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq;
use MivarTest\PHPUnitHelper;


class ConvertTextIntoSlovaTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $text = "Ресовать Папа пришёл домой и лёг спать. А потом и мама пришла домой.";
        $res = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        foreach ($res as $sentence) {
            foreach ($sentence as $key => $value) {
                //Пример, как достать слова
                //print_r($key."\n");
                //print_r($value);
            }
        }
    }

    public function testConvert()
    {

    }


}