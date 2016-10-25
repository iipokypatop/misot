<?php

//namespace AotTest\Functional\Sviaz\Processor\AotGraph;

use Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors\ChangeWordClassForPointsWithNumericWord;

class ProcessorAotGraphTest extends \AotTest\AotDataStorage
{

    public function testBuildGraphBySentence()
    {
        $sentence_words = [
            'Призывался-Филипповский',
            'сельсовет',
        ];
        $sentence = 'Призывался-Филипповский сельсовет';
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runBySentenceWords($sentence_words);
    }

    public function testLaunchAndBuildGraph()
    {
        $sentence_words = [
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
        $graph = $aot_graph->runBySentenceWords($sentence_words);

        /** @var \Aot\Graph\Slovo\Vertex $vertex */
        foreach ($graph->getVertices() as $vertex) {
            $vertex->setAttribute('graphviz.label', $vertex->getSlovo()->getText());
        }
        $graphviz = new \Graphp\GraphViz\GraphViz();
        $graphviz->createImageSrc($graph);
    }

    public function testBuildGraphWithMezhdometie()
    {
        $sentence = [
            'хай',
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
                if (!$vertex->hasPositionInSentence()) {
                    continue;
                }
                $position_in_sentence = $vertex->getPositionInSentence();
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
        $map = [];
        foreach ($graph->getVerticesCollection() as $vertex) {
            $map[$vertex->getPositionInSentence()][] = $vertex;
        }
//        $map = current($graph->getMapVerticesByPositions());
        $this->assertCount(1, $map[0]);
        $this->assertCount(1, $map[1]);
        $this->assertCount(2, $map[2]);
    }

    public function testLaunchAndBuildGraphWithFilterBySameEdges()
    {
        $sentence = [
            'телефона',
            'офиса',
            '1',
        ];
        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $aot_graph->addFilters([
            \Aot\Sviaz\Processors\AotGraph\Filters\RemoveEdgesThatLinkSameVertices\Base::create(),
        ]);

        $graph = $aot_graph->runBySentenceWords($sentence);

        $this->assertEquals(4, $graph->getVertices()->count());
        $this->assertEquals(4, $graph->getEdges()->count());

        $map = [];
        foreach ($graph->getVerticesCollection() as $vertex) {
            $map[$vertex->getPositionInSentence()][] = $vertex;
        }
        $this->assertCount(1, $map[0]);
        $this->assertCount(1, $map[1]);
        $this->assertCount(2, $map[2]);
    }

    public function testLaunchAndBuildGraphWithFilterBySameVertices()
    {
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

    public function testLaunchAndBuildGraphWithFilterRemoveEdgesThatLinkSameVertices()
    {
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
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_3, $vertex_poshel_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_chelovek_3, $vertex_poshel_1, $rule);


        \Aot\Graph\Slovo\Edge::create($vertex_poshel_1, $vertex_dom_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_poshel_1, $vertex_dom_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_poshel_1, $vertex_dom_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_poshel_1, $vertex_dom_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_poshel_2, $vertex_dom_1, $rule);


        \Aot\Graph\Slovo\Edge::create($vertex_dom_1, $vertex_bolshoy_1, $rule);
        \Aot\Graph\Slovo\Edge::create($vertex_dom_1, $vertex_bolshoy_2, $rule);

        $filter = \Aot\Sviaz\Processors\AotGraph\Filters\RemoveEdgesThatLinkSameVertices\Base::create();
        $filter->run($graph);

        $this->assertEquals(8, $graph->getVertices()->count());
        $this->assertEquals(10, $graph->getEdges()->count());
    }

    /**
     * Тестирование менеджера создания синтаксической модели
     */
    public function testSyntaxModelManager()
    {
        $sentence = 'телефон офиса 1';
        $syntax_manager = \Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\Base::create();
        $syntax_manager->addPostProcessors([
            ChangeWordClassForPointsWithNumericWord::create(),
        ]);
        $syntax_model = $syntax_manager->run($sentence);
        foreach ($syntax_model as $item) {
            if ($item->getKw() === 2) {
                $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID, $item->dw->id_word_class);
                $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL, $item->dw->name_word_class);
            }
        }
    }

    /**
     * Тестирование пост обработчика, заменяющего класс точки в синтаксической модели
     */
    public function testPostProcessorThatChangeWordClass()
    {
        $syntax_model = [];

        // не заменяется
        $dw1 = \WrapperAot\ModelNew\Convert\DictionaryWord::create(
            null,
            null,
            '1',
            \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID,
            \WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL,
            []
        );
        /** @var \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point1 */
        $point1 = $this->getMockBuilder(\WrapperAot\ModelNew\Convert\SentenceSpaceSPRel::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();
        $point1->dw = $dw1;

        // заменяется
        $dw2 = \WrapperAot\ModelNew\Convert\DictionaryWord::create(
            null, null, '2555', 3, 'прилагательное', []
        );

        /** @var \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point2 */
        $point2 = $this->getMockBuilder(\WrapperAot\ModelNew\Convert\SentenceSpaceSPRel::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();

        $point2->dw = $dw2;

        // не заменяется
        $dw3 = \WrapperAot\ModelNew\Convert\DictionaryWord::create(
            null, null, 'один', 3, 'прилагательное', []
        );

        /** @var \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point3 */
        $point3 = $this->getMockBuilder(\WrapperAot\ModelNew\Convert\SentenceSpaceSPRel::class)
            ->disableOriginalConstructor()
            ->setMethods(['_'])
            ->getMock();

        $point3->dw = $dw3;

        $syntax_model[] = $point1;
        $syntax_model[] = $point2;
        $syntax_model[] = $point3;

        $post_processor = ChangeWordClassForPointsWithNumericWord::create();
        $syntax_model = $post_processor->run($syntax_model);
        $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID, $syntax_model[0]->dw->id_word_class);
        $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL, $syntax_model[0]->dw->name_word_class);

        $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID, $syntax_model[1]->dw->id_word_class);
        $this->assertEquals(\WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL, $syntax_model[1]->dw->name_word_class);

        $this->assertEquals(3, $syntax_model[2]->dw->id_word_class);
        $this->assertEquals('прилагательное', $syntax_model[2]->dw->name_word_class);
    }
}