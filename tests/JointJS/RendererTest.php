<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 11.08.2015
 * Time: 13:30
 */

namespace AotTest\JointJS;


class RendererTest extends \AotTest\AotDataStorage
{
    public function testRendersCorrect()
    {
        $rendeder = \Aot\JointJS\Renderer::create();

        $result = $rendeder->renderSequence($this->getRawSequence());
    }
}