<?php


namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation;


use Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation;

class ReplaceByPosition implements ISubstitute
{
    /**
     * @return static
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
        if (empty($collocations)) {
            return;
        }

        foreach ($collocations as $collocation) {
            assert(is_a($collocation, ContainerCollocation::class, true));
        }

        foreach ($collocations as $collocation) {
            /** @var \Aot\Graph\Slovo\Vertex $vertex_of_collocation */
            $vertex_of_collocation = current($collocation->getVerticesOfCollocation());
            $sentence_id = $vertex_of_collocation->getSentenceId();
            $start_position = $collocation->getStartPosition();
            $end_position = $collocation->getEndPosition() - 1;


            // Создаём вершину словосочетания
            $new_vertex = \Aot\Graph\Slovo\Vertex::create(
                $graph,
                $collocation->getCollocationSlovo(),
                $sentence_id,
                $collocation->getStartPosition()
            );

            // удаление всех рёбер внутри словосочетания
            foreach ($graph->getEdgesCollection() as $edge) {
                $pos_start = $edge->getVertexStart()->getPositionInSentence();
                $pos_end = $edge->getVertexEnd()->getPositionInSentence();

                if ($pos_start >= $start_position && $pos_start <= $end_position
                    &&
                    $pos_end >= $start_position && $pos_end <= $end_position
                ) {
                    $edge->destroy();
                }
            }

            $cache = [];

            /** @var \Aot\Graph\Slovo\Vertex $vertex */
            foreach ($vertex_of_collocation->getGraph()->getVerticesCollection() as $vertex) {

                if ($vertex === $new_vertex) {
                    continue;
                }

                $current_vertex_position = $vertex->getPositionInSentence();

                if ($current_vertex_position >= $start_position && $current_vertex_position <= $end_position) {
                    /** @var \Aot\Graph\Slovo\Edge $edge */
                    foreach ($vertex->getEdgesCollection() as $edge) {

                        /** @var \Aot\Graph\Slovo\Vertex $vertex_edge_start */
                        $vertex_edge_start = $edge->getVertexStart();

                        /** @var \Aot\Graph\Slovo\Vertex $vertex_edge_end */
                        $vertex_edge_end = $edge->getVertexEnd();

                        if ($vertex_edge_start !== $vertex) {
                            if (isset($cache[$vertex_edge_start->getId()][$new_vertex->getId()])) {
                                continue;
                            }
                            $cache[$vertex_edge_start->getId()][$new_vertex->getId()] = 1;
                            \Aot\Graph\Slovo\Edge::create($vertex_edge_start, $new_vertex, $edge->getRule());
                            $edge->destroy();
                        }

                        if ($vertex_edge_end !== $vertex) {
                            if (isset($cache[$new_vertex->getId()][$vertex_edge_end->getId()])) {
                                continue;
                            }
                            $cache[$vertex_edge_start->getId()][$new_vertex->getId()] = 1;
                            \Aot\Graph\Slovo\Edge::create($new_vertex, $vertex_edge_end, $edge->getRule());
                            $edge->destroy();
                        }

                    }
                    $vertex->destroy();
                }
            }
        }

        $this->fixPositionsInGraph($graph);
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    protected function fixPositionsInGraph(\Aot\Graph\Slovo\Graph $graph)
    {
        //TODO именно здесь надо будет сдвигать позиции, если это понадобится
    }
}