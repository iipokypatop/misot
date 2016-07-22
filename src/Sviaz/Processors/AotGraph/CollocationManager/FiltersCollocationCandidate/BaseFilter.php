<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.07.2016
 * Time: 15:40
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate;


class BaseFilter implements IFilter
{
    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate\BaseFilter
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[] $collocation_candidates
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Additions\ContainerCollocation[]
     */
    public function filter(array $collocation_candidates)
    {
        //TODO Необходимо фильтровать и выбирать самые большие словосочетания

        //TODO необходимо обработать пересечение словосочетаний
        return $collocation_candidates;
    }
}