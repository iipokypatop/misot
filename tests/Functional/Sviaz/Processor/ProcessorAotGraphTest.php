<?php

class ProcessorAotGraphTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $sentence = [
            'папа',
            'пошел',
            'в',
            'лес',
            ',',
            'чтобы',
            'искать',
            'грибы',
            ',',
            'а',
            'потом',
            'съесть',
            'их',
            '.',
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $aot_graph->run($sentence);
    }


}