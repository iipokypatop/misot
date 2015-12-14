<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 015, 15.10.2015
 * Time: 18:12
 */

namespace Aot\Graph;

abstract class Graph extends \Fhaculty\Graph\Graph implements \Aot\Graph\IGraph
{
    /**
     * @var \Aot\Graph\Graph
     */
    protected $previous;

    /**
     * @param \Aot\Graph\IGraph $previous
     * @return $this
     */
    public function wrap(\Aot\Graph\IGraph $previous)
    {
        $this->previous = $previous;
    }

    /**
     * @param int $steps_back
     * @return \Aot\Graph\Graph
     */
    public function getPrevious($steps_back = 1)
    {
        assert(is_int($steps_back));
        if ($steps_back < 0) {
            throw new \RuntimeException('Invalid steps_back value');
        }
        if ($steps_back === 0) {
            return $this;
        }

        if ($steps_back === 1) {
            return $this->previous;
        }

        if ($steps_back > 1) {
            return $this->previous->getPrevious($steps_back - 1);
        }
    }

    /**
     *
     */
    public function getWrappedClasses()
    {

        if ($this->getPrevious() === null) {
            return [static::class];
        }
        $res = $this->getPrevious()->getWrappedClasses();
        array_unshift($res, static::class);
        return $res;

    }

    /**
     * return set of Vertices added to this graph
     *
     * @return \Fhaculty\Graph\Set\Vertices
     */
    public function getVertices()
    {
        return parent::getVertices();
    }

    /**
     * return set of ALL Edges added to this graph
     *
     * @return \Fhaculty\Graph\Set\Edges
     */
    public function getEdges()
    {
        return parent::getEdges();
    }

    /**
     * Создание клона вершины
     * @param \Aot\Graph\Graph $new_graph
     * @param \Aot\Graph\Vertex $originalVertex
     * @return \Aot\Graph\Vertex
     */
    protected function createVertexCloneCustom(\Aot\Graph\Graph $new_graph, \Aot\Graph\Vertex $originalVertex){
        throw new \LogicException("The method is not implemented");
    }

    /**
     * Создание клона ребра
     * @param \Aot\Graph\Edge $originalEdge
     * @param \Aot\Graph\Vertex $start_vertex
     * @param \Aot\Graph\Vertex $end_vertex
     * @return \Aot\Graph\Edge
     */
    protected function createEdgeCloneCustom(\Aot\Graph\Edge $originalEdge, \Aot\Graph\Vertex $start_vertex, \Aot\Graph\Vertex $end_vertex){
        throw new \LogicException("The method is not implemented");
    }

    /**
     * Создание клона графа
     *
     * @return \Aot\Graph\Graph
     */
    public function createGraphCloneCustom(){
        throw new \LogicException("The method is not implemented");
    }
}