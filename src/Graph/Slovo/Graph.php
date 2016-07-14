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
        $pos = $vertex->getPositionInSentence();
        if (!is_int($pos) || $pos < 0) {
            throw new \Aot\Exception("Wrong position value! " . var_export($pos, true));
        }
        $this->map_vertices_by_positions[$pos][spl_object_hash($vertex)] = $vertex;
        $this->map_positions_by_vertices[spl_object_hash($vertex)][$pos] = $pos;
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex[][]
     */
    public function getMapVerticesByPositions()
    {
        return $this->map_vertices_by_positions;
    }

    /**
     * @return int[][]
     */
    public function getMapPositionsByVertices()
    {
        return $this->map_positions_by_vertices;
    }
}