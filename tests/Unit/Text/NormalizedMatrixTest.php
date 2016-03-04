<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/07/15
 * Time: 16:16
 */

namespace AotTest\Unit\Text;


use Aot\Text\Matrix;
use Aot\Text\NormalizedMatrix;
use MivarTest\PHPUnitHelper;
use MivarTest\RunkitMock;

class NormalizedMatrixTest extends \AotTest\AotDataStorage
{
    public function testNormalize()
    {
        # подменяем поведение метода
        $data = [
            1 => [$this->getUniqueString() => $this->getUniqueString()],
            2 => $this->getUniqueString()
        ];


        # получаем подделку NormalizedMatrix
        $normalizedMatrix = $this->getMock(NormalizedMatrix::class, ['build'], [], '', false);

        $i = RunkitMock::interceptFunction('is_array'); // подменяем функцию

        $i->setResult(true, [$data[1]]);
        $i->setResult(false, [$data[2]]);


        # получаем подделку Matrix
        $matrix = $this->getMock(Matrix::class, ['getSentenceMatrix'], [], '', false);

        # подменяем поле
        PHPUnitHelper::setProtectedProperty($normalizedMatrix, 'matrix', $matrix);


        $matrix
            ->expects($this->once())
            ->method('getSentenceMatrix')
            ->with()
            ->will($this->returnValue($data));

        $matrix_id_mask = [];
        $test_index = 0;

        foreach ($data as $index => $element) {
            if ($index === 1) {
                foreach ($element as $value) {


                    $matrix_id_mask[$index][] = $value;

                }
            } elseif ($index === 2) {


                $matrix_id_mask[$index][] = $element;
            }
        }



        $res = PHPUnitHelper::callProtectedMethod($normalizedMatrix, 'normalize', []);
        $this->assertNull($res); // метод ничего не возвращает, поэтому долно равняться ClassNull
        $this->assertEquals($matrix_id_mask, PHPUnitHelper::getProtectedProperty($normalizedMatrix, 'matrix_id_mask'));

        $this->assertEquals([[$data[1]], [$data[2]]], $i->getCalledParams());
    }

}

