<?php

namespace Aot\Sviaz\Processors\AotGraph;

class Base
{
    // главная точка в АОТе
    const MAIN_POINT = 'x';

    // зависимая точка в АОТе
    const DEPENDED_POINT = 'y';

    /** @var \Aot\Sviaz\Processors\AotGraph\Builder */
    protected $builder;

    /** @var \Aot\Sviaz\Processors\AotGraph\SentenceManager */
    protected $sentence_manager;

    /** @var  \Aot\RussianMorphology\Slovo[][][] */
    protected $slova_collection = [];

    /** @var  \Aot\Sviaz\Processors\AotGraph\Filters\Base[] */
    protected $filters = [];

    /** @var  \Aot\Sviaz\Processors\AotGraph\CollocationManager\Manager */
    protected $collocation_manager;

    /** @var bool */
    protected $use_initial_form_aot_as_word_form = false;

    /**
     * @return static
     */
    public static function create()
    {
        $ob = new static();
        $ob->collocation_manager = CollocationManager\Manager::createDefault();
        return $ob;
    }

    protected function __construct()
    {
        $this->builder = Builder::create();
    }

    /**
     * @param \Aot\Sviaz\Processors\AotGraph\Filters\Base[] $filters
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            assert(is_a($filter, \Aot\Sviaz\Processors\AotGraph\Filters\Base::class, true));
        }
        $this->filters = array_merge($this->filters, $filters);
    }

    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @return \Aot\Graph\Slovo\Graph
     */
    public function run(\Aot\Sviaz\Sequence $sequence)
    {
        $sentence_words = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                $sentence_words[] = $member->getPunctuaciya()->getText();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition) {
                /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                $sentence_words[] = $member->getPredlog()->getText();
                $sentence_words[] = $member->getSlovo()->getText();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                $sentence_words[] = $member->getSlovo()->getText();
            }
        }

        return $this->runBySentenceWords($sentence_words);
    }

    /**
     * @param string[] $sentence_words
     * @param int $sentence_id
     * @return \Aot\Graph\Slovo\Graph
     */
    public function runBySentenceWords(array $sentence_words, $sentence_id = 0)
    {
        assert(is_int($sentence_id));
        foreach ($sentence_words as $sentence_word) {
            assert(is_string($sentence_word));
        }

        $this->sentence_manager = \Aot\Sviaz\Processors\AotGraph\SentenceManager::create($sentence_words);

        $syntax_model = $this->createSyntaxModel($this->sentence_manager->getSentence());

        $syntax_model = $this->clearFromUnknownEntities($syntax_model);

        if (empty($syntax_model)) {
            return $this->builder->buildGraph();
        }

        if ($this->sentence_manager->hasOffset($syntax_model)) {
            $this->use_initial_form_aot_as_word_form = true;
        }

        $links = $this->getLinkedSlova($syntax_model);
        $graph = $this->createGraph($links, $sentence_id);

        $this->runFilters($graph);

        $this->collocation_manager->run($graph);
        return $graph;
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return Link[]
     */
    protected function getLinkedSlova(array $syntax_model)
    {
        foreach ($syntax_model as $point) {
            assert(is_a($point, \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel::class, true));
        }

        if (empty($syntax_model)) {
            return [];
        }


        /**
         * $slova_collection:
         * для каждого [point->kw][point->initial_form] - свой объект Slovo
         * Человек пошел летом гулять:
         *                         /----> (гулять)
         * (человек) ----> (пойти) -----> (летом)
         *           \              \----> (лето)
         *           \---> (пошлый)
         */


        /** @var \Aot\Sviaz\Processors\AotGraph\Link[] $links */
        $links = [];

        /** @var  \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model */
        foreach ($syntax_model as $key => $point) {

            $slovo = $this->buildSlovo($point);

            if (!isset($links[$point->Oz])) {
                $link = \Aot\Sviaz\Processors\AotGraph\Link::create($point->O);
                $links[$point->Oz] = $link;
            } else {
                $link = $links[$point->Oz];
            }

            if (in_array(
                $link->getNameOfLink(),
                \Aot\Sviaz\Processors\AotGraph\SubConjunctionRegistry::$sub_conjunctions)
            ) {
                $link->setDirectLink(false);
            }

            if ($point->direction === static::MAIN_POINT) {
                $link->setMainSlovo($slovo);
                $link->setMainPosition($point->kw);
                continue;

            }

            if ($point->direction === static::DEPENDED_POINT) {
                $link->setDependedSlovo($slovo);
                $link->setDependedPosition($point->kw);
                continue;
            }

            throw new \Aot\Exception('Unknown point direction: ' . var_export($point->direction, true));
        }

        return $links;
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point
     * @return \Aot\RussianMorphology\Slovo
     */
    protected function buildSlovo(\WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point)
    {
        if (empty($this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)])) {

            $factory_slovo = $this->builder->getFactorySlovo($point->dw->id_word_class);
            if (!$this->use_initial_form_aot_as_word_form) {
                $point->dw->word_form = $this->sentence_manager->getSentenceWordByAotId($point->kw);
            } else {
                $point->dw->word_form = $point->dw->initial_form;
            }
            $slovo = $factory_slovo->build($point->dw)[0];
            $this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)] = $slovo;
        } else {
            $slovo = $this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)];
        }

        return $slovo;
    }

    /**
     * @param array $parameters
     * @return string
     */
    protected function getHashParameters(array $parameters = [])
    {
        ksort($parameters);
        return md5(serialize($parameters));
    }

    /**
     * Строим граф
     *
     * @param \Aot\Sviaz\Processors\AotGraph\Link[] $links
     * @param int $sentence_id
     * @return \Aot\Graph\Slovo\Graph
     */
    protected function createGraph(array $links, $sentence_id)
    {
        $graph_slova = $this->builder->buildGraph();

        if (empty($links)) {
            return $graph_slova;
        }

        $vertices_manager = \Aot\Sviaz\Processors\AotGraph\VerticesManager::create(
            $graph_slova,
            $this->builder
        );

        foreach ($links as $link) {
            if (!$link->isDirectLink()) {
                $vertex_union = $this->builder->buildSoyuzVertex($graph_slova, $sentence_id, $link);
                $this->builder->buildEdge(
                    $vertices_manager->getVertexBySlovo(
                        $link->getMainSlovo(),
                        $sentence_id,
                        $link->getMainPosition()
                    ),
                    $vertex_union,
                    $link->getNameOfLink()
                );

                $this->builder->buildEdge(
                    $vertex_union,
                    $vertices_manager->getVertexBySlovo(
                        $link->getDependedSlovo(),
                        $sentence_id,
                        $link->getDependedPosition()
                    ),
                    $link->getNameOfLink()
                );
                continue;
            }
            $this->builder->buildEdge(
                $vertices_manager->getVertexBySlovo(
                    $link->getMainSlovo(),
                    $sentence_id,
                    $link->getMainPosition()
                ),
                $vertices_manager->getVertexBySlovo(
                    $link->getDependedSlovo(),
                    $sentence_id,
                    $link->getDependedPosition()
                ),
                $link->getNameOfLink()
            );
        }

        // http://redmine.mivar.ru/issues/3183
        $this->filterExcessEdgeFromAOT($graph_slova);

        return $graph_slova;
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph_slova
     */
    protected function filterExcessEdgeFromAOT(\Aot\Graph\Slovo\Graph $graph_slova)
    {
        /** @var \BaseGraph\Edge $edge */
        foreach ($graph_slova->getEdges() as $edge) {
            if ($edge->getVertexStart() === $edge->getVertexEnd()) {
                $edge->destroy();
            }
        }
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     */
    protected function runFilters(\Aot\Graph\Slovo\Graph $graph)
    {
        foreach ($this->filters as $filter) {
            $filter->run($graph);
        }
    }

    /**
     * @param string $sentence
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    protected function createSyntaxModel($sentence)
    {
        $syntax_manager = \Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\Base::create();
        $syntax_manager->addPostProcessors([
            SyntaxModelManager\PostProcessors\ChangeWordClassForPointsWithNumericWord::create(),
        ]);
        return $syntax_manager->run($sentence);
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    protected function clearFromUnknownEntities(array $syntax_model)
    {
        $relations_with_unknown = [];
        foreach ($syntax_model as $id => $item) {
            if (!is_int($item->dw->id_word_class)) {
                $relations_with_unknown[] = $item->getOz();
                unset($syntax_model[$id]);
            }
        }
        $relations_with_unknown = array_unique($relations_with_unknown);

        // todo: пока отношения с неизвестными не удаляем, потом мб придется
//        foreach ($syntax_model as $id => $item) {
//            if (in_array($item->getOz(), $relations_with_unknown, true)) {
//
//            }
//        }
        return $syntax_model;
    }
}