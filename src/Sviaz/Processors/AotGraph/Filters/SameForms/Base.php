<?php

namespace Aot\Sviaz\Processors\AotGraph\Filters\SameForms;

/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 26/07/16
 * Time: 18:58
 */
class Base extends \Aot\Sviaz\Processors\AotGraph\Filters\Base
{
    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return \Aot\Graph\Slovo\Graph
     */
    public function run(\Aot\Graph\Slovo\Graph $graph)
    {
        $vertices_in_position = current($graph->getMapVerticesByPositions());

        foreach ($vertices_in_position as $position_vertex_current => $vertices) {

            if (count($vertices) === 1) {
                continue;
            }

            $cache = [];
            /** @var \Aot\Graph\Slovo\Vertex[] $map */
            $map = [];
            /** @var \Aot\Graph\Slovo\Vertex $vertex */
            foreach ($vertices as $vertex) {
                /** @var \Aot\Graph\Slovo\Edge $edge */
                foreach ($vertex->getEdgesCollection() as $edge) {
                    /** @var \Aot\Graph\Slovo\Vertex $vertex_start */
                    $vertex_start = $edge->getVertexStart();
                    /** @var \Aot\Graph\Slovo\Vertex $vertex_end */
                    $vertex_end = $edge->getVertexEnd();
                    $position_vertex_start = $vertex_start->getPositionInSentence();
                    $position_vertex_end = $vertex_end->getPositionInSentence();

                    if ($position_vertex_start === $position_vertex_end) {
                        throw new \Aot\Exception('Invalid edge, link vertices from same position!' . var_export($edge, true));
                    }

                    $linked_vertex = $vertex === $vertex_start
                        ? $vertex_end
                        : $vertex_start;

                    $cache[spl_object_hash($vertex)][spl_object_hash($linked_vertex)] = $linked_vertex;
                    $map[spl_object_hash($vertex)] = $vertex;
                }
            }

            $delete = [];
            $visited = [];
            foreach ($cache as $key1 => $item1) {

                foreach ($cache as $key2 => $item2) {
                    if ($key1 === $key2) {
                        continue;
                    }
                    if (isset($visited[$key1][$key2])) {
                        continue;
                    }
                    $visited[$key1][$key2] = 1;
                    $visited[$key2][$key1] = 1;
                    if ($item1 === $item2) {
                        $delete[$key2] = $key2;
                    }
                }
            }

            foreach ($delete as $key) {
                $map[$key]->destroy();
            }

        }
    }
}