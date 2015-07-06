<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 11:32
 */

namespace AotTest\Functional\Language;


class FirstTest extends \AotTest\AotDataStorage
{
    public function testFirst()
    {
        //$suschestbitelnoe = \RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe\Base::create('стол');

        //$suschestbitelnoe->padeszh
        //suschestbitelnoe\pade
        //
        $a = "suschestvitelnoe.padeszh = suschestvitelnoe.morphology.padeszh.imenitelnij";


        $a = str_replace('.', '->', $a);
        $a = str_replace('=', '==', $a);
        $a = str_replace('suschestvitelnoe', '$suschestvitelnoe', $a);

        //$a = str_replace('after', '', $a);

//        echo $a;

//        $this->assertTrue(false);
    }
}

