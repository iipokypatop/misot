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
            'потому',
            'что',
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
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runBySentenceWords($sentence);

        //TODO данные для сравнения теста подобраны костыльно, определяется "хотя бы было бы столько-то вершин и столько-то рёбер"
        $this->assertGreaterThan($cnt_vertices_and_edges[0], $graph->getVertices()->count());
        $this->assertGreaterThan($cnt_vertices_and_edges[1], $graph->getEdges()->count());
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
                [10, 5]
            ],
            [
                [
                    'мама',
                    'пошла',
                    'летом',
                    'гулять',
                ],
                [4, 6]
            ],
            [
                [
                    'папа',
                    'пошел',
                    'в',
                    'лес',
                ],
                [3, 2]
            ],
        ];
    }



    public function testAddSoyuzOnGraph()
    {
        $sentence = [
            'папа',
            'пошел',
            'в',
            'лес',
            ',',
            'если',
            'искать',
            'гриб',
            '.',
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runBySentenceWords($sentence);

        /** @var \Aot\Graph\Slovo\Vertex $vertex */
        foreach ($graph->getVertices() as $vertex) {
            if ($vertex->getSlovo()->getText() === 'если') {
                break;
            }
        }
        $edge_in = $vertex->getEdgesIn();
        $edge_out = $vertex->getEdgesOut();

        /** @var \Aot\Graph\Slovo\Vertex $vertex_start */
        $vertex_start = $edge_in->getEdgeFirst()->getVerticesStart()->getVertexFirst();
        /** @var \Aot\Graph\Slovo\Vertex $vertex_target */
        $vertex_target = $edge_out->getEdgeLast()->getVerticesTarget()->getVertexFirst();

        $this->assertEquals(2, $vertex->getEdges()->count());
        $this->assertEquals('пойти', $vertex_start->getSlovo()->getInitialForm());
        $this->assertEquals('искать', $vertex_target->getSlovo()->getInitialForm());
    }
}