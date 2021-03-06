<?php
namespace Aot\Graph\Slovo;

use Aot\Graph\Slovo\Position\Map;

class Graph extends \BaseGraph\Graph
{
    /** @var  \Aot\Graph\Slovo\Position\Map */
    protected $position_map;

    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public static function create()
    {
        return new static();
    }

    public function __construct()
    {
        parent::__construct();
        $this->position_map = Map::create();
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return int
     */
    public function getVertexPositionInSentence(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->position_map->getPositionInSentence($vertex);
    }

    /**
     * @return \Aot\Graph\Slovo\Edge[]
     */
    public function getEdgesCollection()
    {
        return parent::getEdgesCollection();
    }

    /**
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    public function getVerticesCollection()
    {
        return parent::getVerticesCollection();
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param int $sentence_id
     * @param int $position_in_sentence
     */
    public function appendVertexInPositionMap(\Aot\Graph\Slovo\Vertex $vertex, $sentence_id, $position_in_sentence)
    {
        assert(is_int($sentence_id));
        assert(is_int($position_in_sentence) && $position_in_sentence >= 0);
        $this->position_map->add($vertex, $sentence_id, $position_in_sentence);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return bool
     */
    public function isVertexHasPosition(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->position_map->hasPosition($vertex);
    }


    /**
     * @param Vertex $vertex_for_delete
     * @return bool
     */
    public function deleteVertexFromPositionMap(\Aot\Graph\Slovo\Vertex $vertex_for_delete)
    {
        $this->position_map->delete($vertex_for_delete);
    }

    /**
     * @return Map
     */
    public function getPositionMap()
    {
        return $this->position_map;
    }
}