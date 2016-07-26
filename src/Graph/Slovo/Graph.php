<?php
namespace Aot\Graph\Slovo;

class Graph extends \BaseGraph\Graph
{
    /** @var \Aot\Graph\Slovo\Vertex[][] */
    protected $map_vertices_by_positions = [];

    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     */
    public function appendVertexInMapPositionsOfVerticesInSentence(\Aot\Graph\Slovo\Vertex $vertex)
    {
        $position_in_sentence = $vertex->getPositionInSentence();
        $sentence_id = $vertex->getSentenceId();
        if (!is_int($position_in_sentence) || $position_in_sentence < 0) {
            throw new \Aot\Exception("Wrong position value! " . var_export($position_in_sentence, true));
        }
        $this->map_vertices_by_positions[$sentence_id][$position_in_sentence][spl_object_hash($vertex)] = $vertex;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex[][][]
     */
    public function getMapVerticesByPositions()
    {
        return $this->map_vertices_by_positions;
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     */
    public function removeVertexFromPositionMaps(\Aot\Graph\Slovo\Vertex $vertex)
    {
        $hash = spl_object_hash($vertex);
        if (!isset($this->map_vertices_by_positions[$vertex->getSentenceId()][$vertex->getPositionInSentence()][$hash])) {
            throw new \Aot\Exception('The vertex is not set in vertices by positions map!' . var_export($vertex, true));
        }
        unset($this->map_vertices_by_positions[$vertex->getSentenceId()][$vertex->getPositionInSentence()][$hash]);
    }
}