<?php
namespace Aot\Sviaz\Processors\AotGraph\Filters\RemoveEdgesThatLinkSameVertices;

class Base extends \Aot\Sviaz\Processors\AotGraph\Filters\Base
{

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    public function run(\Aot\Graph\Slovo\Graph $graph)
    {
        $map = [];
        foreach ($graph->getEdgesCollection() as $edge) {
            $vertex_start_id = $edge->getVertexStart()->getId();
            $vertex_end_id = $edge->getVertexEnd()->getId();
            if (isset($map[$vertex_start_id][$vertex_end_id])) {
                $edge->destroy();
            } else {
                $map[$vertex_start_id][$vertex_end_id] = 1;
            }
        }
    }
}