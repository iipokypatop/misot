<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 26/10/15
 * Time: 17:37
 */

namespace Aot\Graph;


use Fhaculty\Graph\Set\Edges;
use Fhaculty\Graph\Set\Vertices;

interface IGraph
{
    /**
     * return set of Vertices added to this graph
     *
     * @return Vertices
     */
    public function getVertices();

    /**
     * return set of ALL Edges added to this graph
     *
     * @return Edges
     */
    public function getEdges();
}