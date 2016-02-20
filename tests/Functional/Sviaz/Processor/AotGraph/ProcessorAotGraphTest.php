<?php

//namespace AotTest\Functional\Sviaz\Processor\AotGraph;

class ProcessorAotGraphTest extends \AotTest\AotDataStorage
{
    public function testLaunchAndBuildGraph()
    {
        $sentence = [
            'Папа',
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
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runByWords($sentence);

        /** @var \Aot\Graph\Slovo\Vertex $vertex */
        foreach ($graph->getVertices() as $vertex) {
            $vertex->setAttribute('graphviz.label', $vertex->getSlovo()->getText());
        }
        /** @var \Aot\Graph\Slovo\Edge $edge */
        foreach ($graph->getEdges() as $edge) {
            if (null !== $edge->getPredlog()) {
                $edge->setAttribute('graphviz.label', $edge->getPredlog()->getText());
            }
        }

        $graphviz = new \Graphp\GraphViz\GraphViz();
    }

    /**
     * @dataProvider dataProviderSerializedSyntaxModels
     * @param $sentence
     * @param $cnt_vertices_and_edges
     */
    public function testCorrectGraph($sentence, $cnt_vertices_and_edges)
    {
        $aot_graph = \AotTest\Functional\Sviaz\Processor\AotGraph\AotGraphSyntaxModelMock::create();
        $graph = $aot_graph->runByWords($sentence);

        $this->assertEquals($graph->getVertices()->count(), $cnt_vertices_and_edges[0]);
        $this->assertEquals($graph->getEdges()->count(), $cnt_vertices_and_edges[1]);
    }

    public function dataProviderSerializedSyntaxModels()
    {
        return [
            [
                [
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
                ],
                [9,8]
            ],
            [
                [
                    'мама',
                    'пошла',
                    'летом',
                    'гулять',
                ],
                [6,8]
            ],
            [
                [
                    'папа',
                    'пошел',
                    'в',
                    'лес',
                ],
                [3,2]
            ],
        ];
    }

}