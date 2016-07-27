<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 12:57
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation;


interface ISubstitute
{
    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[] $collocations
     */
    public function run(\Aot\Graph\Slovo\Graph $graph, array $collocations);
}