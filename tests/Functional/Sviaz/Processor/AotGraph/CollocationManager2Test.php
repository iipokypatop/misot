<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 15:33
 */

namespace Functional\Sviaz\Processor\AotGraph;

use MivarTest\PHPUnitHelper;

use Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate;
use Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate;
use Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation;

class CollocationManager2Test extends \PHPUnit_Framework_TestCase
{

    public function testNeedStructureDBPostgres()
    {
        $config = \MivarUtils\Common\Config::getConfig();
        $db = $config['text']['db'];

        $data = [
            [
                'sql' => "select count(*) from pg_tables where tablename='wcombi'",
                'error' => "В текущей БД () нет таблицы 'wcombii'",
            ],
            [
                'sql' => "select count(*) from pg_tables where tablename='wcombi_item'",
                'error' => "В текущей БД () нет таблицы 'wcombi_item'",
            ],
        ];

        $api = \TextPersistence\API\TextAPI::getAPI();

        $em = $api->getEntityManager();

        foreach ($data as $item) {
            $sql = $item['sql'];
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $this->assertEquals(1, $result[0]['count'], $item['error']);
        }

    }

    public function testRun()
    {
        $graph = $this->buildGraph();
        // Для тех, кто хочет посмотреть, как выглядит граф
//        $this->printGraph($graph);

        $this->assertEquals(6, count($graph->getVerticesCollection()),
            'Число вершин в исходном графе не совпадает с ожиданиями');
        $this->assertEquals(5, count($graph->getEdgesCollection()),
            'Число рёбер в исходном графе не совпадает с ожиданиями');


        $collocation_manager = \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager::create();

        $mock_api = $this->getMockApi();
        $factory_collocation_candidates = FactoriesCollocationCandidate\BaseFactory::create();
        $factory_collocation_candidates->setApi($mock_api);
        $substitute_words_in_collocation = SubstitutesWordsInCollocation\BaseSubstitute::create();
        $filter_collocation_candidate = FiltersCollocationCandidate\BaseFilter::create();

        $collocation_manager->setFactoryCollocationCandidates($factory_collocation_candidates);
        $collocation_manager->addFiltersCollocationCandidate($filter_collocation_candidate);
        $collocation_manager->setSubstituteWordsInCollocation($substitute_words_in_collocation);

        $collocation_manager->run($graph);

        //Теперь следует череда ASSERT'ов
        $this->assertEquals(5, count($graph->getVerticesCollection()));
        $this->assertEquals(4, count($graph->getEdgesCollection()));

        $map_vertices_by_positions = $graph->getMapVerticesByPositions();
        $this->assertEquals('я', current($map_vertices_by_positions[0][0])->getSlovo()->getText());
        $this->assertEquals('увидел', current($map_vertices_by_positions[0][1])->getSlovo()->getText());
        $this->assertEquals('папу римского', current($map_vertices_by_positions[0][2])->getSlovo()->getText());
        $this->assertTrue(empty($map_vertices_by_positions[0][3]));
        $this->assertEquals('в', current($map_vertices_by_positions[0][4])->getSlovo()->getText());
        $this->assertEquals('лесу', current($map_vertices_by_positions[0][5])->getSlovo()->getText());


        //TODO дописать ассерты по желанию


        // Для тех, кто хочет посмотреть, как выглядит граф
//        $this->printGraph($graph);

    }

    protected function printGraph(\Aot\Graph\Slovo\Graph $graph)
    {
        echo "------------------------------------\n";
        /** @var \Aot\Graph\Slovo\Vertex $vertex */
        foreach ($graph->getVerticesCollection() as $vertex) {
            $position = join('', array_values($graph->getMapPositionsByVertices()[spl_object_hash($vertex)][0]));
            echo $vertex->getSlovo()->getText() . " (" . $vertex->getSlovo()->getInitialForm() . ") - " . $position . "\n";
        }

        echo "\n";

        /** @var \Aot\Graph\Slovo\Edge $vertex */
        foreach ($graph->getEdgesCollection() as $edge) {
            /** @var \Aot\Graph\Slovo\Vertex $vertex_1 */
            $vertex_1 = $edge->getVertexStart();
            /** @var \Aot\Graph\Slovo\Vertex $vertex_2 */
            $vertex_2 = $edge->getVertexEnd();
            echo
                $vertex_1->getSlovo()->getText() . " (" . $vertex_1->getSlovo()->getInitialForm() . ")" . "  --->  " .
                $vertex_2->getSlovo()->getText() . " (" . $vertex_2->getSlovo()->getInitialForm() . ")" . "\n";
        }
        echo "------------------------------------\n";

    }

    /**
     *
     *                      3           4
     *  1        2        /->(папу)--->(римского)
     * (я)--->(увидел)--<
     *                    \->(в)--->(лесу)
     *                        5        6
     *
     *
     * @return \Aot\Graph\Slovo\Graph
     */
    protected function buildGraph()
    {
        $graph = \Aot\Graph\Slovo\Graph::create();

        $slovo_ya = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_ya, 'text', 'я');
        PHPUnitHelper::setProtectedProperty($slovo_ya, 'initial_form', 'я');
        $vertex_ya = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_ya, 0, 0);

        $slovo_uvidel = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_uvidel, 'text', 'увидел');
        PHPUnitHelper::setProtectedProperty($slovo_uvidel, 'initial_form', 'увидеть');
        $vertex_uvidel = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_uvidel, 0, 1);

        $slovo_papy = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_papy, 'text', 'папу');
        PHPUnitHelper::setProtectedProperty($slovo_papy, 'initial_form', 'папа');
        $vertex_papy = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_papy, 0, 2);

        $slovo_rimskogo = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_rimskogo, 'text', 'римского');
        PHPUnitHelper::setProtectedProperty($slovo_rimskogo, 'initial_form', 'римский');
        $vertex_rimskogo = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_rimskogo, 0, 3);

        $slovo_v = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_v, 'text', 'в');
        PHPUnitHelper::setProtectedProperty($slovo_v, 'initial_form', 'в');
        $vertex_v = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_v, 0, 4);

        $slovo_lesu = $this->getMockBuilder(\Aot\RussianMorphology\Slovo::class)
            ->disableOriginalConstructor()
            ->setMethods(["_"])
            ->getMock();
        PHPUnitHelper::setProtectedProperty($slovo_lesu, 'text', 'лесу');
        PHPUnitHelper::setProtectedProperty($slovo_lesu, 'initial_form', 'лес');
        $vertex_lesu = \Aot\Graph\Slovo\Vertex::create($graph, $slovo_lesu, 0, 5);

        $edge_ya_uvidel = \Aot\Graph\Slovo\Edge::create(
            $vertex_ya,
            $vertex_uvidel,
            $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        $edge_uvidel_papu = \Aot\Graph\Slovo\Edge::create(
            $vertex_uvidel,
            $vertex_papy,
            $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        $edge_papu_rimskogo = \Aot\Graph\Slovo\Edge::create(
            $vertex_papy,
            $vertex_rimskogo,
            $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        $edge_uvidel_v = \Aot\Graph\Slovo\Edge::create(
            $vertex_uvidel,
            $vertex_v,
            $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        $edge_v_lesu = \Aot\Graph\Slovo\Edge::create(
            $vertex_v,
            $vertex_lesu,
            $this->getMockBuilder(\Aot\Sviaz\Rule\Base::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

//        PHPUnitHelper::setProtectedProperty(\Aot\Graph\Slovo\Vertex::class, 'id_increment', 10);

        return $graph;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | \Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API\API
     */
    protected function getMockApi()
    {
        $api = $this->getMockBuilder(\Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API\API::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getMapCollocationIdsByFirstInitialForm',
                'getMapCollocationInitialFormByCollocationId',
                'getMapInitialFormsByCollocationId',
                'getMapMainPositionByCollocationId'
            ])
            ->getMock();

        $api->method('getMapCollocationIdsByFirstInitialForm')
            ->willReturn([
                'папа' => [
                    1,
                    2
                ]
            ]);

        $api->method('getMapCollocationInitialFormByCollocationId')
            ->willReturn([
                1 => 'Папа Римский',
                2 => 'Папа Костя',
            ]);

        $api->method('getMapInitialFormsByCollocationId')
            ->willReturn([
                1 => [
                    'папа',
                    'римский'
                ],
                2 => [
                    'папа',
                    'костя'
                ],
            ]);

        $api->method('getMapMainPositionByCollocationId')
            ->willReturn([
                1 => 1,
                2 => 1,
            ]);

        return $api;
    }

}
