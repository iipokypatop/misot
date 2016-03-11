<?php

namespace Aot\Sviaz\Processors\AotGraph;

/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 11/12/15
 * Time: 01:08
 */
class Base
{
    const MAIN_POINT = 'x'; // главная точка в АОТе
    const DEPENDED_POINT = 'y'; // зависимая точка в АОТе
    const RELATION = 'relation'; // отношение

    /** @var \Aot\Sviaz\Processors\AotGraph\Builder */
    protected $builder;

//    /** @var \Aot\RussianMorphology\Slovo[] */
//    protected $hash_slovo_map = [];// карта слов

    /** @var \Aot\Sviaz\Processors\AotGraph\SentenceManager */
    protected $sentence_manager;
    /** @var  \Aot\RussianMorphology\Slovo[][][] */
    protected $slova_collection;

    protected function __construct()
    {
        $this->builder = Builder::create();
    }

    public static function create()
    {
        return new static();
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

        return $this->runByWords($sentence_words);
    }

    /**
     * @param string[] $sentence_words
     * @return \Aot\Graph\Slovo\Graph
     */
    public function runByWords(array $sentence_words)
    {
        foreach ($sentence_words as $sentence_word) {
            assert(is_string($sentence_word));
        }

        $this->sentence_manager = \Aot\Sviaz\Processors\AotGraph\SentenceManager::create($sentence_words);

        $syntax_model = $this->createSyntaxModel($this->sentence_manager->getSentence());

        if (empty($syntax_model)) {
            return $this->builder->buildGraph();
        }

        list($links, $prepose_to_slovo) = $this->getLinkedSlova($syntax_model, $this->sentence_manager);

        return $this->createGraph($links, $prepose_to_slovo);
    }

    /**
     * Создание синтаксической модели через АОТ
     * @param string $sentence
     * @return \Sentence_space_SP_Rel[]
     */
    protected function createSyntaxModel($sentence)
    {
        assert(is_string($sentence));

        $mivar = new \DMivarText(['txt' => $sentence]);

        $mivar->syntax_model();

        return $mivar->getSyntaxModel();
    }

    /**
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @return array []
     */
    protected function getLinkedSlova(array $syntax_model)
    {
        foreach ($syntax_model as $point) {
            assert(is_a($point, \Sentence_space_SP_Rel::class, true));
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
        $slova_collection = [];

        $links = [];

        $link_with_prepose = [];

        /** @var  \Sentence_space_SP_Rel[] $syntax_model */
        foreach ($syntax_model as $key => $point) {

            $slovo = $this->buildSlovo($point);

            if ($point->O === \DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {

                $link_with_prepose[$point->Oz][$point->direction] = $slovo;

            } else {

                $links[$point->Oz][2] = $point->O;

                if ($point->direction === static::MAIN_POINT) {

                    $links[$point->Oz][0] = $slovo;
                    continue;
                }

                if ($point->direction === static::DEPENDED_POINT) {
                    $links[$point->Oz][1] = $slovo;
                    continue;
                }

                throw new \LogicException('Unknown point direction: ' . var_export($point->direction));

            }
        }

        $links_filtered = $this->filterLinks($links);

        $prepose_to_slovo = [];
        foreach ($link_with_prepose as $oz => $pair) {
            if (is_a($pair[static::MAIN_POINT], \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class, true)) {
                $prepose_to_slovo[spl_object_hash($pair[static::DEPENDED_POINT])] = $pair[static::MAIN_POINT];
            }
        }

        return [$links_filtered, $prepose_to_slovo];
    }

    /**
     * @param \Sentence_space_SP_Rel $point
     * @return \Aot\RussianMorphology\Slovo
     */
    protected function buildSlovo(\Sentence_space_SP_Rel $point)
    {

        if (empty($this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)])) {

            $factory_slovo = $this->builder->getFactorySlovo($point->dw->id_word_class);

            $point->dw->word_form = $this->sentence_manager->getSentenceWordByAotId($point->kw);

            $slovo = $factory_slovo->build($point->dw)[0];

            $this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)] = $slovo;

            //$this->hash_slovo_map[spl_object_hash($slovo)] = $slovo;
        } else {

            $slovo = $this->slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)];
        }


        return $slovo;
    }

    /**
     * @param  $parameters
     * @return string
     */
    protected function getHashParameters(array $parameters = [])
    {
        ksort($parameters);
        return md5(serialize($parameters));
    }

    /**
     * @param mixed[] $links_in
     * @return mixed[]
     */
    protected function filterLinks(array $links_in)
    {
        $links = [];
        foreach ($links_in as $link) {
            if (
                $link[0] instanceof \Aot\RussianMorphology\ChastiRechi\Predlog\Base
                || $link[1] instanceof \Aot\RussianMorphology\ChastiRechi\Predlog\Base
            ) {
                continue;
            }

            $links[] = $link;
        }

        return $links;
    }

    /**
     * Строим граф
     *
     * @param \Aot\RussianMorphology\Slovo[][] $links
     * @param \Aot\RussianMorphology\Slovo[] $prepose_to_slovo
     * @return \Aot\Graph\Slovo\Graph
     */
    protected function createGraph(array $links, array $prepose_to_slovo = [])
    {
        $graph_slova = $this->builder->buildGraph();

        if (empty($links)) {
            return $graph_slova;
        }

        $vertices_manager = \Aot\Sviaz\Processors\AotGraph\VerticesManager::create(
            $graph_slova,
            $this->builder,
            $prepose_to_slovo
        );

        foreach ($links as $link) {

            if (empty($link[0]) || empty($link[1]) || empty($link[2])) {
                throw new \LogicException("Main point or depended point or relation is empty!");
            }

            $this->builder->buildEdge(
                $vertices_manager->getVertexBySlovo($link[0]),
                $vertices_manager->getVertexBySlovo($link[1]),
                $link[2]
            );
        }

        return $graph_slova;
    }

}