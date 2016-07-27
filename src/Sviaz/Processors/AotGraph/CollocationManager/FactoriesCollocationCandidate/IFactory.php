<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 12:56
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate;


interface IFactory
{
    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    public function createCollocationCandidatesByGraph(\Aot\Graph\Slovo\Graph $graph);
}