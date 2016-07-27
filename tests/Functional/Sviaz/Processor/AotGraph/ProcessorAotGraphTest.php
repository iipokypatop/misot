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
                $map_positions_by_vertices = $graph->getMapPositionsByVertices();
                $map_of_this_vertex = $map_positions_by_vertices[spl_object_hash($vertex)];
                $sentence_id = current(array_keys($map_of_this_vertex));
                $position_in_sentence = current($map_of_this_vertex[$sentence_id]);
                /** @var \Aot\Graph\Slovo\Vertex $vertex */
                $this->assertEquals($id, $sentence_id);
                $this->assertArrayHasKey($position_in_sentence, $sentence_without_punctuation[$id]);
                $this->assertEquals($vertex->getSlovo()->getText(),
                    $sentence_without_punctuation[$id][$position_in_sentence]);
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

    public function testLaunchAndBuildGraphWithFilters()
    {
        $this->markTestSkipped('Отключен, пока не будет выполнена задача с позициями');
        $sentence = [
            'телефон',
            'офиса',
            '1',
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $aot_graph->addFilters([
            \Aot\Sviaz\Processors\AotGraph\Filters\BySameLinkedVertices\Base::create(),
        ]);
        $graph = $aot_graph->runBySentenceWords($sentence);
        $this->assertEquals(4, $graph->getVertices()->count());
        $this->assertEquals(3, $graph->getEdges()->count());
        $map = current($graph->getMapVerticesByPositions());
        $this->assertCount(1, $map[0]);
        $this->assertCount(1, $map[1]);
        $this->assertCount(2, $map[2]);
    }

    public function testLaunchAndBuildGraphWithFilters2()
    {
        $this->markTestSkipped('Отключен, пока не будет выполнена задача с позициями');
        /**
         * Условный граф из предложения:
         * "Человек пошел в большой дом"
         */

        $graph = \Aot\Graph\Slovo\Graph::create();

        /** 3 человека */
        $slovo_chelovek_1 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        $slovo_chelovek_2 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        $slovo_chelovek_3 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();

        /** 2 пойти */
        $slovo_poshel_1 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        $slovo_poshel_2 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Glagol\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();

        /** 2 большой */
        $slovo_bolshoy_1 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        $slovo_bolshoy_2 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();

        /** 1 дом */
        $slovo_dom_1 = $this->getMockBuilder(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();



        $vertex_chelovek_1 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_chelovek_1, 0, 0);
        $vertex_chelovek_2 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_chelovek_2, 0, 0);
        $vertex_chelovek_3 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_chelovek_3, 0, 0);

        $vertex_poshel_1 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_poshel_1, 0, 1);
        $vertex_poshel_2 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_poshel_2, 0, 1);

        $vertex_bolshoy_1 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_bolshoy_1, 0, 2);
        $vertex_bolshoy_2 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_bolshoy_2, 0, 2);

        $vertex_dom_1 = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_dom_1, 0, 3);

        $rule = $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_1, $vertex_poshel_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_2, $vertex_poshel_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_3, $vertex_poshel_1, $rule);

        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_1, $vertex_poshel_2, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_2, $vertex_poshel_2, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_3, $vertex_poshel_2, $rule);


        \Aot\Graph\Slovo\Edge::create($vertex_poshel_1, $vertex_dom_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_poshel_2, $vertex_dom_1, $rule);

        \Aot\Graph\Slovo\Edge::create($vertex_dom_1, $vertex_bolshoy_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_dom_1, $vertex_bolshoy_2, $rule);


        $filter = \Aot\Sviaz\Processors\AotGraph\Filters\BySameLinkedVertices\Base::create();
        $filter->run($graph);

        $this->assertEquals(4, $graph->getVertices()->count());
        $this->assertEquals(3, $graph->getEdges()->count());
    }
}