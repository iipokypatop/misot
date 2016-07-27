<?php

namespace Aot\Graph\Slovo;


class PositionMap extends \SplObjectStorage
{
    /** @var  \Aot\Graph\Slovo\Vertex[][][] */
    protected $map_vertices_by_positions = [];

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
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param int $position_in_sentence
     * @param int $sentence_id
     */
    public function add(\Aot\Graph\Slovo\Vertex $vertex, $sentence_id, $position_in_sentence)
    {
        $this->map_vertices_by_positions[$sentence_id][$position_in_sentence][spl_object_hash($vertex)] = $vertex;
        $this->attach($vertex, [$sentence_id, $position_in_sentence]);
    }

    /**
     * @param int $sentence_id
     * @param int $position_in_sentence
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    public function getVerticesByPosition($sentence_id, $position_in_sentence)
    {
        if (!isset($this->map_vertices_by_positions[$sentence_id][$position_in_sentence])) {
            throw new \Aot\Exception('Is not set any vertices by input positions!');
        }
        return $this->map_vertices_by_positions[$sentence_id][$position_in_sentence];
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     */
    public function delete(\Aot\Graph\Slovo\Vertex $vertex)
    {
        if (!$this->hasPosition($vertex)) {
            throw new \Aot\Exception('The vertex is not set in position map!');
        }
        $position = $this->getPosition($vertex);
        $hash = spl_object_hash($vertex);
        if (!isset($this->map_vertices_by_positions[$position[0]][$position[1]][$hash])) {
            throw new \Aot\Exception('The vertex is not set in vertices by positions map!');
        }
        unset($this->map_vertices_by_positions[$position[0]][$position[1]][$hash]);
        $this->detach($vertex);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return bool
     */
    public function hasPosition(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return parent::contains($vertex);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return int[]
     */
    public function getPosition(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->offsetGet($vertex);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return int
     */
    public function getPositionInSentence(\Aot\Graph\Slovo\Vertex $vertex)
    {
        if (!$this->hasPosition($vertex)) {
            throw new \Aot\Exception('The vertex does is not exists in the map!');
        }
        $position = $this->getPosition($vertex);
        return $position[1];
    }

}