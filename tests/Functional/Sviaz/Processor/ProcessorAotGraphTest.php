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
            'гриб',
            ',',
            'а',
            'потом',
            'его',
            'съесть',
            '.',
        ];
        // Мама пошла летом гулять
//        $sentence = [
//            'мама',
//            'пошла',
//            'летом',
//            'гулять',
//        ];
        // Папа пошел в лес
//        $sentence = [
//            'папа',
//            'пошел',
//            'в',
//            'лес',
//        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $aot_graph->run($sentence);
    }


}