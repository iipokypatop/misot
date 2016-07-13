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
        $graphviz = new \Graphp\GraphViz\GraphViz();
        $graphviz->createImageSrc($graph);
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
                [10, 9]
            ],
            [
                [
                    'мама',
                    'пошла',
                    'летом',
                    'гулять',
                ],
                [6, 8]
            ],
            [
                [
                    'папа',
                    'пошел',
                    'в',
                    'лес',
                ],
                [4, 3]
            ],
        ];
    }

}