<?php
namespace Aot\Graph\Slovo;

class Graph extends \BaseGraph\Graph
{
    /** @var \Aot\Graph\Slovo\Vertex[][] */
    protected $map_vertices_by_positions = [];

    /** @var int[][] */
    protected $map_positions_by_vertices = [];

    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Removes a single attribute with the given $name
     *
     * @param string $name
     */
    public function removeAttribute($name)
    {
        // TODO: Implement removeAttribute() method.
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
        $this->map_positions_by_vertices[spl_object_hash($vertex)][$sentence_id][$position_in_sentence] = $position_in_sentence;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex[][][]
     */
    public function getMapVerticesByPositions()
    {
        return $this->map_vertices_by_positions;
    }

    /**
     * @return int[][][]
     */
    public function getMapPositionsByVertices()
    {
        return $this->map_positions_by_vertices;
    }
}