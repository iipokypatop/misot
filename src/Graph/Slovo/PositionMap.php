<?php

namespace Aot\Graph\Slovo;


class PositionMap extends \SplObjectStorage
{
    /** @var  \Aot\Graph\Slovo\Vertex[][][] */
    protected $map = [];

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
        $this->map[$sentence_id][$position_in_sentence][spl_object_hash($vertex)] = $vertex;
        $this->attach($vertex, [$sentence_id, $position_in_sentence]);
    }

    /**
     * @param int $sentence_id
     * @param int $position_in_sentence
     * @return \Aot\Graph\Slovo\Vertex[]
     */
    public function getVerticesByPosition($sentence_id, $position_in_sentence)
    {
        return $this->map[$sentence_id][$position_in_sentence];
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     */
    public function delete(\Aot\Graph\Slovo\Vertex $vertex)
    {
        $position = $this->getPosition($vertex);
        unset($this->map[$position[0]][$position[1]][spl_object_hash($vertex)]);
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

    /**
     * @return Vertex[][][]
     */
    public function getMap()
    {
        return $this->map;
    }


}