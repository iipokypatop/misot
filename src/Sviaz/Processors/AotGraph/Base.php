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
     * @return array
     */
    protected function createGraph($links)
    {
        if(empty($links)){
            return [];
        }

        $graph_slovo = [];
        $vertices = [];
        $edges = [];
        foreach ($links as $oz => $slova) {

            $main_slovo = $slova[static::MAIN_POINT];
            $depend_slovo = $slova[static::DEPENDED_POINT];

            if (empty($vertices[spl_object_hash($main_slovo)])) {
                $vertices[spl_object_hash($main_slovo)] = $main_vertex = 1;
            } else {
                $main_vertex = $vertices[spl_object_hash($main_slovo)];
            }

            if (empty($vertices[spl_object_hash($depend_slovo)])) {
                $vertices[spl_object_hash($depend_slovo)] = $depended_vertex = 1;
            } else {
                $depended_vertex = $vertices[spl_object_hash($slova[static::MAIN_POINT])];

            }
            $asserted_main = \Aot\Sviaz\Rule\AssertedMember\Main::create();
            $asserted_depend = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
            $rule = \Aot\Sviaz\Rule\Base::create($asserted_main, $asserted_depend);

            $edges[] = [$main_slovo, $depend_slovo, $rule/*, $depended_vertex->getPredlog()*/];
//            \Core\Graph\Slovo\Edge::create($main_vertex, $depended_vertex, $sviaz->getRule(), $depended_vertex->getPredlog());
        }

        print_r($edges);
        return $graph_slovo;
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

        /** @var  \Sentence_space_SP_Rel $point */
        foreach ($syntax_model as $key => $point) {
            $factory = $this->builder->getFactory($point->dw->id_word_class);
            $point->dw->word_form = $sentence_driver->getSentenceWordByAotId($point->kw);
            $slova = $factory->build($point->dw);
            $links[$point->Oz][$point->direction] = $slova[0];
        }

        return $links;
    }


}