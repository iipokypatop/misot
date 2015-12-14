<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\Graph;


interface IEdge
{
    /**
     * @param \Aot\Graph\IEdge $previous
     */
    public function wrap(\Aot\Graph\IEdge $previous);

    /**
     * @param int $steps_back
     * @return \Aot\Graph\IEdge
     */
    public function getPrevious($steps_back = 1);

    /**
     * @return \Aot\Graph\IVertex
     */
    public function getVertexStart();

    /**
     * @return \Aot\Graph\IVertex
     */
    public function getVertexEnd();
}