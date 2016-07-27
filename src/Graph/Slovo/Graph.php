<?php
namespace Aot\Graph\Slovo;

class Graph extends \BaseGraph\Graph
{
    /** @var \Aot\Graph\Slovo\Vertex[][][] */
    protected $map_vertices_by_positions = [];

    /** @var int[][][] */
    protected $map_positions_by_vertices = [];

    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param int $sentence_id
     * @param int $position_in_sentence
     */
    public function appendVertexInMapPositionsOfVerticesInSentence(
        \Aot\Graph\Slovo\Vertex $vertex,
        $sentence_id,
        $position_in_sentence
    ) {
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

    /**
     * @param Vertex $vertex_for_delete
     * @return bool
     */
    public function deleteVertexFromMaps(\Aot\Graph\Slovo\Vertex $vertex_for_delete)
    {
        $hash_vertex_for_delete = spl_object_hash($vertex_for_delete);
        foreach ($this->map_positions_by_vertices as $hash => $positions) {
            if ($hash_vertex_for_delete === $hash) {
                unset($this->map_positions_by_vertices[$hash]);
                break;
            }
        }

        foreach ($this->map_vertices_by_positions as $sentence_id => $words_id) {
            foreach ($words_id as $word_id => $vertices) {
                foreach ($vertices as $hash => $vertex) {
                    if ($hash_vertex_for_delete === $hash) {
                        unset ($this->map_vertices_by_positions[$sentence_id][$word_id][$hash]);
                    }
                }
            }
        }
    }
}