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

    /** @var \Aot\Sviaz\Processors\AotGraph\Builder */
    protected $builder;

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
     * @return array
     */
    public function run(array $sentence_words)
    {
        $sentence_driver = \Aot\Sviaz\Processors\AotGraph\SentenceDriver::create($sentence_words);

        $syntax_model = $this->createSyntaxModel($sentence_driver);

        $links = $this->getLinkedPoints($syntax_model, $sentence_driver);

        $this->createGraph($links);

        return [];
    }

    /**
     * Строим граф
     *
     * @param \Aot\RussianMorphology\Slovo[][] $links
     * @return \Aot\Graph\Slovo\Graph
     */
    protected function createGraph($links)
    {
        if (empty($links)) {
            return [];
        }

        $vertices = [];

        $graph_slova = $this->builder->buildGraph();

        foreach ($links as $oz => $slova) {

            if (empty($slova[static::MAIN_POINT]) || empty($slova[static::DEPENDED_POINT])) {
                throw new \LogicException("Main or depended point is empty!");
            }

            $main_slovo = $slova[static::MAIN_POINT];
            $depend_slovo = $slova[static::DEPENDED_POINT];

            if (empty($vertices[spl_object_hash($main_slovo)])) {
                $main_vertex = $this->builder->buildVertex($graph_slova, $main_slovo);
                $vertices[spl_object_hash($main_slovo)] = $main_vertex;
            } else {
                $main_vertex = $vertices[spl_object_hash($main_slovo)];
            }

            if (empty($vertices[spl_object_hash($depend_slovo)])) {
                $depended_vertex = $this->builder->buildVertex($graph_slova, $depend_slovo);
                $vertices[spl_object_hash($depend_slovo)] = $depended_vertex;
            } else {
                $depended_vertex = $vertices[spl_object_hash($slova[static::MAIN_POINT])];

            }

            $this->builder->buildEdge($main_vertex, $depended_vertex);
        }

        print_r([
            $graph_slova->getVertices()->count(),
            $graph_slova->getEdges()->count(),
        ]);
        return $graph_slova;
    }

    /**
     * Создание синтаксической модели через АОТ
     * @param \Aot\Sviaz\Processors\AotGraph\SentenceDriver $sentenceDriver
     * @return \Sentence_space_SP_Rel[]
     */
    protected function createSyntaxModel(\Aot\Sviaz\Processors\AotGraph\SentenceDriver $sentenceDriver)
    {
        $mivar = new \DMivarText(['txt' => $sentenceDriver->getSentence()]);

        $mivar->syntax_model();

        return $mivar->getSyntaxModel();
    }

    /**
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @param \Aot\Sviaz\Processors\AotGraph\SentenceDriver $sentence_driver
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    protected function getLinkedPoints(array $syntax_model, \Aot\Sviaz\Processors\AotGraph\SentenceDriver $sentence_driver)
    {
        foreach ($syntax_model as $point) {
            assert(is_a($point, \Sentence_space_SP_Rel::class, true));
        }

        if (empty($syntax_model)) {
            return [];
        }

        $links = [];

        $link_with_prepose = [];
        /** @var  \Sentence_space_SP_Rel $point */
        foreach ($syntax_model as $key => $point) {
            $factory_slovo = $this->builder->getFactorySlovo($point->dw->id_word_class);
            $point->dw->word_form = $sentence_driver->getSentenceWordByAotId($point->kw);
            $slova = $factory_slovo->build($point->dw);

            if( is_a($slova[0], \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class, true)){
                $link_with_prepose[$point->Oz][$point->direction] = $slova[0];
            }
            $links[$point->Oz][$point->direction] = $slova[0];
        }


        return $links;
    }


}