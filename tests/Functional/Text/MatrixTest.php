<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:25
 */

namespace AotTest\Functional\Text;


class MatrixTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $this->getMatrix();
    }

    public function testCantAppendWordTwice()
    {
        $mixed = $this->getWordsAndPunctuation();

        $matrix = new \Aot\Text\Matrix;

        try {
            foreach ($mixed as $value) {

                if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                    $matrix->appendWordsForm($value);
                }

                if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                    $matrix->appendWordsForm($value);
                }


            }
            $this->fail();

        } catch (\RuntimeException $e) {


            $this->assertTrue(true);
        }
    }
}