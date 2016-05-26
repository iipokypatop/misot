<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.05.2016
 * Time: 9:24
 */
namespace AotTest\Functional\Sviaz\Processor\AotGraph;

class Issue3183Test extends \AotTest\AotDataStorage
{
    // http://redmine.mivar.ru/issues/3183
    public function testIssue()
    {
        $words = [
            'Аптека',
            'улица',
            'фонарь',
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runByWords($words);
        $this->assertEquals(3, count($graph->getVertices()));
        $this->assertEquals(0, count($graph->getEdges()));
    }
}
