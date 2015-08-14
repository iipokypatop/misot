<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 14:37
 */

namespace AotTest\JointJS\Misc;


class PositionTest extends \MivarTest\Base
{
    public function testWorks()
    {
        $ob = \Aot\JointJS\Objects\Position::create(1, 2);

        $data = json_encode($ob);

        $this->assertEquals('{"x":1,"y":2}', $data);
    }
}