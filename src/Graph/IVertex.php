<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\Graph;

interface IVertex
{
    /**
     * @return int
     */
    public static function getNextId();

    /**
     * @param \Aot\Graph\IVertex $previous
     */
    public function wrap(\Aot\Graph\IVertex $previous);

    /**
     * @param int $steps_back
     * @return \Aot\Graph\IVertex
     */
    public function getPrevious($steps_back = 1);

    public function getWrappedClasses();
}