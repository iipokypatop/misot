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

    /** @var \Aot\RussianMorphology\Slovo[] */
    protected $hash_slovo_map = [];// карта слов

    /** @var \Aot\Sviaz\Processors\AotGraph\SentenceManager */
    protected $sentence_manager;

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->builder = Builder::create();
    }

    /**
     * @param string[] $sentence_words
     * @return \Aot\Graph\Slovo\Graph
     */
    public function run(array $sentence_words)
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
     * @return \Aot\RussianMorphology\Slovo[][]
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
            if (empty($slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)])) {
                $factory_slovo = $this->builder->getFactorySlovo($point->dw->id_word_class);
                $point->dw->word_form = $this->sentence_manager->getSentenceWordByAotId($point->kw);
                $slovo = $factory_slovo->build($point->dw)[0];
                $slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)] = $slovo;
                $this->hash_slovo_map[spl_object_hash($slovo)] = $slovo;
            } else {
                $slovo = $slova_collection[$point->kw][$point->dw->initial_form][$this->getHashParameters($point->dw->parameters)];
            }

            if ($point->O === \DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
                $link_with_prepose[$point->Oz][$point->direction] = $slovo;
            } else {

                if ($point->direction === static::MAIN_POINT) {
                    $links[$point->Oz][0] = $slovo;
                } elseif ($point->direction === static::DEPENDED_POINT) {
                    $links[$point->Oz][1] = $slovo;
                } else {
                    throw new \LogicException('Unknown point direction: ' . var_export($point->direction));
                }

                $links[$point->Oz][2] = $point->O;;
            }
        }

        $prepose_to_slovo = [];
        foreach ($link_with_prepose as $oz => $pair) {
            $prepose_to_slovo[spl_object_hash($pair[static::DEPENDED_POINT])] = $pair[static::MAIN_POINT];
        }

        return [$links, $prepose_to_slovo];
    }

    /**
     * @param  array $parameters
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