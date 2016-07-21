<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 13:49
 */

namespace Functional\Sviaz\Processor\AotGraph\CollocationManager\FactoriesCollocationCandidate\API;


class APITest extends \PHPUnit_Framework_TestCase
{
    public function testLaunch()
    {
        \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API\API::get();
    }
}
