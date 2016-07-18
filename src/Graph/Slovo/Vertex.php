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

    /** @var  int */
    protected $sentence_id;

    /**
     * @param Graph $graph
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @param int $sentence_id
     * @param int $position_in_sentence
     * @return static
     */
    public static function create(Graph $graph, \Aot\RussianMorphology\Slovo $slovo, $sentence_id, $position_in_sentence)
    {
        assert(is_int($sentence_id));
        assert(is_int($position_in_sentence));
        $obj = new static($graph, static::getNextId());
        $obj->slovo = $slovo;
        $obj->position_in_sentence = $position_in_sentence;
        $obj->sentence_id = $sentence_id;
        $graph->appendVertexInMapPositionsOfVerticesInSentence($obj);
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
     * @return int
     */
    public function getPositionInSentence()
    {
        return $this->position_in_sentence;
    }

    /**
     * @return int
     */
    public function getSentenceId()
    {
        return $this->sentence_id;
    }

}