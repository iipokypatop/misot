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
        $graph = $aot_graph->runBySentenceWords($sentence);

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
        $graph = $aot_graph->runBySentenceWords($sentence);

        $this->assertEquals($graph->getVertices()->count(), $cnt_vertices_and_edges[0]);
        $this->assertEquals($graph->getEdges()->count(), $cnt_vertices_and_edges[1]);
    }

    public function testCorrectPositions()
    {
        $sentences = [
            [
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
            ],
            [
                'Он',
                'там',
                'потерялся',
                '!',
            ]
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        /** @var \Aot\Graph\Slovo\Graph[] $graphs */
        $graphs = [];
        $graphs[] = $aot_graph->runBySentenceWords($sentences[0], 0);
        $graphs[] = $aot_graph->runBySentenceWords($sentences[1], 1);
        $sentence_without_punctuation = [];
        foreach ($sentences as $id => $sentence) {
            foreach ($sentence as $item) {
                if (!preg_match("/[\\.\\,\\?\\!]/ui", $item)) {
                    $sentence_without_punctuation[$id][] = $item;
                }
            }
        }

        foreach ($graphs as $id => $graph) {
            foreach ($graph->getVerticesCollection() as $vertex) {
                /** @var \Aot\Graph\Slovo\Vertex $vertex */
                $this->assertArrayHasKey($vertex->getPositionInSentence(), $sentence_without_punctuation[$id]);
                $this->assertEquals($vertex->getSlovo()->getText(), $sentence_without_punctuation[$id][$vertex->getPositionInSentence()]);
                $this->assertEquals($id, $vertex->getSentenceId());
            }
        }
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