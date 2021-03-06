<?php

namespace Aot\Graph\Slovo\Position;


class Map
{
    /** @var  \Aot\Graph\Slovo\Vertex[][][] */
    protected $map_vertices_by_positions = [];

    /** @var \SplObjectStorage */
    protected $storage;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param int $position_in_sentence
     * @param int $sentence_id
     */
    public function add(\Aot\Graph\Slovo\Vertex $vertex, $sentence_id, $position_in_sentence)
    {
        $this->map_vertices_by_positions[$sentence_id][$position_in_sentence][spl_object_hash($vertex)] = $vertex;
        $coordinate = \Aot\Graph\Slovo\Position\Coordinate::create($sentence_id, $position_in_sentence);
        $this->storage->attach($vertex, $coordinate);
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
        $coordinate = $this->getPosition($vertex);
        $hash = spl_object_hash($vertex);
        $sentence_id = $coordinate->getSentenceId();
        $position_in_sentence = $coordinate->getPositionInSentence();
        if (!isset($this->map_vertices_by_positions[$sentence_id][$position_in_sentence][$hash])) {
            throw new \Aot\Exception('The vertex is not set in vertices by positions map!');
        }
        unset($this->map_vertices_by_positions[$sentence_id][$position_in_sentence][$hash]);
        $this->storage->detach($vertex);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return bool
     */
    public function hasPosition(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->storage->contains($vertex);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return \Aot\Graph\Slovo\Position\Coordinate
     */
    public function getPosition(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->storage->offsetGet($vertex);
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
        return $position->getPositionInSentence();
    }

}