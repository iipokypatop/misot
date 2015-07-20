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

        $matrix = \Aot\Text\Matrix::create();

        foreach ($mixed as $value) {

            if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                $matrix->appendWordsForm($value);

                try {

                    $matrix->appendWordsForm($value);

                    $this->fail();

                } catch (\RuntimeException $e) {

                    $this->assertEquals(
                        "one word or punctuation can't be here twice " . var_export($value[0], 1),
                        $e->getMessage()
                    );

                }
            }
        }
    }
}