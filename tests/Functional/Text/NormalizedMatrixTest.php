<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 1:05
 */

namespace AotTest\Functional\Text;


class NormalizedMatrixTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $normalized_matrix = $this->getNormalizedMatrix();

        foreach ($normalized_matrix as $array) ;
    }
}
