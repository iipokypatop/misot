<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:10
 */

namespace Aot\Graph\Slovo;

class Vertex extends \BaseGraph\Vertex
{
    /** @var  \Aot\RussianMorphology\Slovo */
    protected $slovo;

    /** @var  int */
    protected $position_in_sentence;

    public static function create(Graph $graph, \Aot\RussianMorphology\Slovo $slovo)
    {
        $obj = new static($graph, static::getNextId());
        $obj->slovo = $slovo;
        return $obj;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getSlovo()
    {
        return $this->slovo;
    }

    /**
     * @param int $position_in_sentence
     */
    public function addPositionInSentence($position_in_sentence)
    {
        assert(is_int($position_in_sentence));
        $this->position_in_sentence = $position_in_sentence;
        /** @var \Aot\Graph\Slovo\Graph $graph */
        $graph = $this->getGraph();
        $graph->appendVertexInMapPositionsOfVerticesInSentence($this);
    }

    /**
     * @return int
     */
    public function getPositionInSentence()
    {
        return $this->position_in_sentence;
    }
}