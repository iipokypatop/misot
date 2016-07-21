<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 12:39
 */

namespace Unit\Sviaz\Processors\AotGraph\CollocationManager;


class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testLaunch()
    {
        \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager::createDefault();
    }

    public function testRun()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager $collocation_manager */
        $collocation_manager = $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();


        $graph = $this->getMockBuilder(\Aot\Graph\Slovo\Graph::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collocation_manager->run($graph);
    }
}
