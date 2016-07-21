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
     *  TODO: по умолчанию стоит null - костыль, для залепы баги из конвертера, как только багу в конвертере поправим - костыль убрать!
     * @param int $position_in_sentence
     * @return static
     */
    public static function create(Graph $graph, \Aot\RussianMorphology\Slovo $slovo, $sentence_id, $position_in_sentence = null)
    {
        assert(is_int($sentence_id));
        assert(is_int($position_in_sentence) || is_null($position_in_sentence));
        $obj = new static($graph, static::getNextId());
        $obj->slovo = $slovo;
        if ($position_in_sentence !== null) {
            $obj->position_in_sentence = $position_in_sentence;
            $graph->appendVertexInMapPositionsOfVerticesInSentence($obj, $sentence_id, $position_in_sentence);
        }
        $obj->sentence_id = $sentence_id;
        return $obj;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getSlovo()
    {
        return $this->slovo;
    }

    //TODO i.yakovenko считает, это нельзя использовать, по всем вопросам обращаться к нему!
//    /**
//     * @return int
//     */
//    public function getPositionInSentence()
//    {
//        return $this->position_in_sentence;
//    }
//
//    /**
//     * @return int
//     */
//    public function getSentenceId()
//    {
//        return $this->sentence_id;
//    }

    public function destroy()
    {
        parent::destroy();
        /** @var \Aot\Graph\Slovo\Graph $graph */
        $graph = $this->getGraph();
        $graph->deleteVertexFromMaps($this);
    }
}