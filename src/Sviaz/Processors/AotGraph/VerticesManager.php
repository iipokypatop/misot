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

    public static function create(\Aot\Graph\Slovo\Graph $graph, \Aot\Sviaz\Processors\AotGraph\Builder $builder, $prepose_to_slovo)
    {
        return new static($graph, $builder, $prepose_to_slovo);
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param Builder $builder
     * @param \Aot\RussianMorphology\Slovo[] $prepose_to_slovo
     */
    protected function __construct(\Aot\Graph\Slovo\Graph $graph, \Aot\Sviaz\Processors\AotGraph\Builder $builder, array $prepose_to_slovo = null)
    {
        $this->graph = $graph;
        $this->builder = $builder;
        $this->prepose_to_slovo = $prepose_to_slovo;
    }

    /**
     * Получение вершины по слову
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function getVertexBySlovo(\Aot\RussianMorphology\Slovo $slovo)
    {

        if (!$this->isProcessedSlovo($slovo)) {
            if ($this->isSlovoHasPrepose($slovo)) {
                $vertex = $this->builder->buildVertex(
                    $this->graph,
                    $slovo,
                    $this->getPreposeBySlovo($slovo)
                );
            } else {
                $vertex = $this->builder->buildVertex($this->graph, $slovo);
            }
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
    protected function isProcessedSlovo($slovo)
    {
        return !empty($this->vertices[spl_object_hash($slovo)]);
    }

    /**
     * Есть ли у слова предлог
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return bool
     */
    protected function isSlovoHasPrepose(\Aot\RussianMorphology\Slovo $slovo)
    {
        return !empty($this->prepose_to_slovo[spl_object_hash($slovo)]);
    }

    /**
     * Получить предлог по слову
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return bool
     */
    protected function getPreposeBySlovo(\Aot\RussianMorphology\Slovo $slovo)
    {
        if (!empty($this->prepose_to_slovo[spl_object_hash($slovo)])) {
            return $this->prepose_to_slovo[spl_object_hash($slovo)];
        }

        throw new \LogicException("Slovo does not have a prepose");
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