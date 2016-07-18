<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 11/12/15
 * Time: 18:03
 */

namespace Aot\Sviaz\Processors\AotGraph;


class VerticesManager
{

    /** @var  \Aot\RussianMorphology\Slovo[] */
    protected $vertices;

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param Builder $builder
     * @return \Aot\Sviaz\Processors\AotGraph\VerticesManager
     */
    public static function create(\Aot\Graph\Slovo\Graph $graph, \Aot\Sviaz\Processors\AotGraph\Builder $builder)
    {
        return new static($graph, $builder);
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param Builder $builder
     */
    protected function __construct(\Aot\Graph\Slovo\Graph $graph, \Aot\Sviaz\Processors\AotGraph\Builder $builder)
    {
        $this->graph = $graph;
        $this->builder = $builder;
    }

    /**
     * Получение вершины по слову
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @param int $position_slovo_in_sentence
     * @param int $sentence_id
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function getVertexBySlovo(\Aot\RussianMorphology\Slovo $slovo, $sentence_id, $position_slovo_in_sentence)
    {
        assert(is_int($position_slovo_in_sentence));
        if (!$this->isProcessedSlovo($slovo)) {
            $vertex = $this->builder->buildVertex($this->graph, $slovo, $sentence_id, $position_slovo_in_sentence);
//            $vertex->addPositionInSentence($position_slovo_in_sentence);
//            $vertex->setSentenceId($sentence_id);
            $this->pushVertex($slovo, $vertex);
        } else {
            $vertex = $this->pullVertex($slovo);
        }

        return $vertex;
    }

    /**
     * Обработано ли слово
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return bool
     */
    protected function isProcessedSlovo(\Aot\RussianMorphology\Slovo $slovo)
    {
        return !empty($this->vertices[spl_object_hash($slovo)]);
    }

    /**
     * Вытянуть вершину
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Graph\Slovo\Vertex
     */
    protected function pullVertex(\Aot\RussianMorphology\Slovo $slovo)
    {
        if ($this->isProcessedSlovo($slovo)) {
            return $this->vertices[spl_object_hash($slovo)];
        }

        throw new \LogicException("Unknown Slovo");
    }

    /**
     * Добавить новую вершину
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @param \Aot\Graph\Slovo\Vertex $vertex
     */
    protected function pushVertex(\Aot\RussianMorphology\Slovo $slovo, \Aot\Graph\Slovo\Vertex $vertex)
    {
        $this->vertices[spl_object_hash($slovo)] = $vertex;
    }
}