<?php

namespace Aot\Sviaz\Processors\AotGraph\Filters\BySameLinkedVertices;

class Base extends \Aot\Sviaz\Processors\AotGraph\Filters\Base
{

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    public function run(\Aot\Graph\Slovo\Graph $graph)
    {
        $deleted_vertices = $this->findDeletedVertices($graph);
        $this->deleteVertices($deleted_vertices);
    }


    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    protected function findDeletedVertices(\Aot\Graph\Slovo\Graph $graph)
    {
        $vertices_in_position = $this->getGroupsVerticesByPositionsInSentence($graph);

        $deleted_vertices = [];

        foreach ($vertices_in_position as $vertices) {

            if ($this->isSingleVertexInPosition($vertices)) {
                continue;
            }

            $linked_vertices = $this->getMapLinkedVerticesToGroup($vertices);

            $deleted_vertices = array_merge(
                $deleted_vertices,
                $this->selectDeletedVerticesFromLinkedVerticesToGroup($graph, $linked_vertices)
            );
        }

        return $deleted_vertices;
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return \Aot\Graph\Slovo\Vertex[][]
     */
    protected function getGroupsVerticesByPositionsInSentence(\Aot\Graph\Slovo\Graph $graph)
    {
        return current($graph->getMapVerticesByPositions());
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex[] $vertices
     * @return bool
     */
    protected function isSingleVertexInPosition(array $vertices)
    {
        return count($vertices) === 1;
    }

    /**
     *
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param int[][] $cache
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    protected function selectDeletedVerticesFromLinkedVerticesToGroup(\Aot\Graph\Slovo\Graph $graph, array $cache)
    {
        $visited = [];
        $deleted_vertices = [];
        foreach ($cache as $key1 => $item1) {

            foreach ($cache as $key2 => $item2) {

                if ($key1 === $key2 || isset($visited[$key1][$key2])) {
                    continue;
                }

                $visited[$key1][$key2] = 1;
                $visited[$key2][$key1] = 1;
                if ($item1 === $item2) {
                    $deleted_vertices[$key2] = $graph->getVertex($key2);
                }
            }
        }
        return $deleted_vertices;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex[] $vertices
     * @return int[][]
     */
    protected function getMapLinkedVerticesToGroup($vertices)
    {
        $map_linked_vertices = [];

        foreach ($vertices as $vertex) {

            /** @var \Aot\Graph\Slovo\Edge $edge */
            foreach ($vertex->getEdgesCollection() as $edge) {

                /** @var \Aot\Graph\Slovo\Vertex $vertex_start */
                $vertex_start = $edge->getVertexStart();

                /** @var \Aot\Graph\Slovo\Vertex $vertex_end */
                $vertex_end = $edge->getVertexEnd();

                if ($vertex_start->getPositionInSentence() === $vertex_end->getPositionInSentence()) {
                    throw new \Aot\Exception('Invalid edge, vertices from same position!' . var_export($edge, true));
                }

                $linked_vertex = $vertex === $vertex_start
                    ? $vertex_end
                    : $vertex_start;

                $map_linked_vertices[$vertex->getId()][$linked_vertex->getId()] = $linked_vertex->getId();
            }

            ksort($map_linked_vertices[$vertex->getId()]);

        }

        return $map_linked_vertices;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex[] $deleted_vertices
     */
    protected function deleteVertices(array $deleted_vertices)
    {
        foreach ($deleted_vertices as $key => $vertex) {
            $vertex->destroy();
        }
    }
}