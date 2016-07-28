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
        $map = [];
        foreach ($graph->getVerticesCollection() as $vertex) {
            $map[$vertex->getPositionInSentence()][] = $vertex;
        }
        return $map;
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
     * @param int[][] $groups_of_linked_vertices
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    protected function selectDeletedVerticesFromLinkedVerticesToGroup(
        \Aot\Graph\Slovo\Graph $graph,
        array $groups_of_linked_vertices
    )
    {
        $compared_groups = [];
        $list_of_deleting_vertices = [];
        foreach ($groups_of_linked_vertices as $vertex_id1 => $vertices_group1) {

            foreach ($groups_of_linked_vertices as $vertex_id2 => $vertices_group2) {

                if ($vertex_id1 === $vertex_id2 || isset($compared_groups[$vertex_id1][$vertex_id2])) {
                    continue;
                }

                $compared_groups[$vertex_id1][$vertex_id2] = 1;
                $compared_groups[$vertex_id2][$vertex_id1] = 1;
                if ($vertices_group1 === $vertices_group2) {
                    $list_of_deleting_vertices[$vertex_id2] = $graph->getVertex($vertex_id2);
                }
            }
        }
        return $list_of_deleting_vertices;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex[] $vertices
     * @return int[][]
     */
    protected function getMapLinkedVerticesToGroup(array $vertices)
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