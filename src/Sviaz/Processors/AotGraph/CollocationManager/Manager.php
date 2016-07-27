<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.07.2016
 * Time: 15:56
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager;

use Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\IFactory;
use Aot\Sviaz\Processors\AotGraph\CollocationManager\FiltersCollocationCandidate\IFilter;
use Aot\Sviaz\Processors\AotGraph\CollocationManager\SubstitutesWordsInCollocation\ISubstitute;


/**
 * Формирование словосочетаний в графе
 */
class Manager
{
    /** @var  IFactory */
    protected $factory_collocation_candidates;
    /** @var  IFilter[] */
    protected $filters_collocation_candidate = [];
    /** @var  ISubstitute */
    protected $substitute_words_in_collocation;

    /**
     * @return static
     */
    public static function createDefault()
    {
        $ob = new static();
        $ob->factory_collocation_candidates = FactoriesCollocationCandidate\BaseFactory::createDefault();
        $ob->filters_collocation_candidate[] = FiltersCollocationCandidate\BaseFilter::create();
        $ob->substitute_words_in_collocation = SubstitutesWordsInCollocation\BaseSubstitute::create();
        return $ob;
    }

    /**
     * @return \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    public function run(\Aot\Graph\Slovo\Graph $graph)
    {
        if ($this->factory_collocation_candidates === null) {
            throw new \Aot\Exception("Не задана фабрика по созданию кандидатов в словосочетания");
        }
        $collocation_candidates = $this->factory_collocation_candidates->createCollocationCandidatesByGraph($graph);

        $collocations = $collocation_candidates;
        foreach ($this->filters_collocation_candidate as $filter_collocation_candidate) {
            $collocations = $filter_collocation_candidate->filter($collocations);
        }

        if ($this->substitute_words_in_collocation === null) {
            throw new \Aot\Exception("Не задан алгоритм внедрения словосочетания");
        }
        $this->substitute_words_in_collocation->run($graph, $collocations);
    }

    /**
     * @return FactoriesCollocationCandidate\IFactory
     */
    public function getFactoryCollocationCandidates()
    {
        return $this->factory_collocation_candidates;
    }

    /**
     * @param FactoriesCollocationCandidate\IFactory $factory_collocation_candidates
     */
    public function setFactoryCollocationCandidates(IFactory $factory_collocation_candidates)
    {
        $this->factory_collocation_candidates = $factory_collocation_candidates;
    }

    /**
     * @return FiltersCollocationCandidate\IFilter[]
     */
    public function getFiltersCollocationCandidate()
    {
        return $this->filters_collocation_candidate;
    }

    /**
     * @param FiltersCollocationCandidate\IFilter $filter_collocation_candidate
     */
    public function addFiltersCollocationCandidate(IFilter $filter_collocation_candidate)
    {
        $this->filters_collocation_candidate[] = $filter_collocation_candidate;
    }

    /**
     * @return SubstitutesWordsInCollocation\ISubstitute
     */
    public function getSubstituteWordsInCollocation()
    {
        return $this->substitute_words_in_collocation;
    }

    /**
     * @param SubstitutesWordsInCollocation\ISubstitute $substitute_words_in_collocation
     */
    public function setSubstituteWordsInCollocation(ISubstitute $substitute_words_in_collocation)
    {
        $this->substitute_words_in_collocation = $substitute_words_in_collocation;
    }


}