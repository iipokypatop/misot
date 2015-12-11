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
        $graph = $aot_graph->run($sentence);

        /** @var \Aot\Graph\Slovo\Vertex $vertex */
        foreach ($graph->getVertices() as $vertex) {
            $vertex->setAttribute('graphviz.label', $vertex->getSlovo()->getText());
        }
        /** @var \Aot\Graph\Slovo\Edge $edge */
        foreach ($graph->getEdges() as $edge) {
            if ( null !== $edge->getPredlog()) {
                $edge->setAttribute('graphviz.label', $edge->getPredlog()->getText());
            }
        }

        $graphviz = new \Graphp\GraphViz\GraphViz();
        print_r($graphviz->createImageHtml($graph));
    }


}