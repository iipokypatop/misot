<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.07.2016
 * Time: 16:59
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation;


use Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation;

class BaseSubstitute implements ISubstitute
{
    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation\BaseSubstitute
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param ContainerCollocation[] $collocations
     */
    public function run(\Aot\Graph\Slovo\Graph $graph, array $collocations)
    {
        foreach ($collocations as $collocation) {
            assert(is_a($collocation, ContainerCollocation::class, true));
        }


        foreach ($collocations as $collocation) {
            $vertices_of_collocation = $collocation->getVerticesOfCollocation();

            // Создаём вершину словосочетания
            $new_vertex = \Aot\Graph\Slovo\Vertex::create(
                $graph,
                $collocation->getCollocationSlovo(),
                0,
                $collocation->getStartPosition()
            );

            //Перекидываем на неё связи
            foreach ($vertices_of_collocation as $vertex_of_collocation) {
                //Рассматриваем каждую вершину словосочетания
                /** @var \Aot\Graph\Slovo\Edge $edge */
                foreach ($vertex_of_collocation->getEdges() as $edge) {
                    if ($edge->getVertexStart() === $vertex_of_collocation) {
                        /** @var \Aot\Graph\Slovo\Vertex $vertex_end */
                        $vertex_end = $edge->getVertexEnd();
                        // Проверяем, входит ли данное ребро внутрь словосочетания
                        foreach ($vertices_of_collocation as $item) {
                            if ($item === $vertex_end) {
                                continue 2;
                            }
                        }
                        $new_edge = \Aot\Graph\Slovo\Edge::create($new_vertex, $vertex_end, $edge->getRule());
                        $edge->destroy();
                    } elseif ($edge->getVertexEnd() === $vertex_of_collocation) {
                        /** @var \Aot\Graph\Slovo\Vertex $vertex_start */
                        $vertex_start = $edge->getVertexStart();
                        // Проверяем, входит ли данное ребро внутрь словосочетания
                        foreach ($vertices_of_collocation as $item) {
                            if ($item === $vertex_start) {
                                continue 2;
                            }
                        }
                        $new_edge = \Aot\Graph\Slovo\Edge::create($vertex_start, $new_vertex, $edge->getRule());
                        $edge->destroy();
                    }
                }
            }


            //Удаляем прошлые вершины
            foreach ($vertices_of_collocation as $vertex_of_collocation) {
                $vertex_of_collocation->destroy();

            }
        }

        $this->fixPositionInGraph($graph);
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    protected function fixPositionInGraph(\Aot\Graph\Slovo\Graph $graph)
    {
        //TODO именно здесь надо будет сдвигать позиции, если это понадобится
    }
}