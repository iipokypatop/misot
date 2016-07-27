<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 12:56
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate;


interface IFilter
{
    /**
     * @param \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[] $collocation_candidates
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    public function filter(array $collocation_candidates);
}