<?php

namespace Aot\Sviaz\Processors\AotGraph;

class Builder
{
    /** @var \Aot\RussianMorphology\FactoryBase[] */
    protected $factories;
    const PREPOSITION_RELATION = 'prepositional_phrase'; // связь с предлогом

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
    }


    /**
     * Создаем правило
     *
     * @param \Aot\Graph\Slovo\Vertex $main_vertex
     * @param \Aot\Graph\Slovo\Vertex $depended_vertex
     * @param string $relation
     * @return \Aot\Sviaz\Rule\Base
     */
    public function buildRule(\Aot\Graph\Slovo\Vertex $main_vertex, \Aot\Graph\Slovo\Vertex $depended_vertex, $relation)
    {
        assert(is_string($relation));

        $id_word_class_main =
            \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getIdByClass(get_class($main_vertex->getSlovo()));

        $id_word_class_depended =
            \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getIdByClass(get_class($depended_vertex->getSlovo()));

        list($role_main, $role_dep) =
            \Aot\Sviaz\Processors\AotGraph\RoleSpecificator::getRoles($relation, $id_word_class_main, $id_word_class_depended);

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        $id_word_class_main,
                        $role_main
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        $id_word_class_depended,
                        $role_dep
                    )
                )
                ->link(
                    \Aot\Sviaz\Rule\Builder\Base::create()
                );
        return $builder->get();
    }

    /**
     * Собираем ребро
     * @param \Aot\Graph\Slovo\Vertex $main_vertex
     * @param \Aot\Graph\Slovo\Vertex $depended_vertex
     * @param string $relation
     * @return \Aot\Sviaz\Rule\Base
     */
    public function buildEdge(\Aot\Graph\Slovo\Vertex $main_vertex, \Aot\Graph\Slovo\Vertex $depended_vertex, $relation)
    {
        assert(is_string($relation));
        if ($relation === static::PREPOSITION_RELATION) {
            \Aot\Graph\Slovo\Edge::create(
                $depended_vertex,
                $main_vertex,
                $this->buildRule($depended_vertex, $main_vertex, $relation)
            );
        } else {
            \Aot\Graph\Slovo\Edge::create(
                $main_vertex,
                $depended_vertex,
                $this->buildRule($main_vertex, $depended_vertex, $relation)
            );
        }
    }

    /**
     * Собираем вершину
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @param int $sentence_id
     * @param int $position_slovo_in_sentence
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function buildVertex(
        \Aot\Graph\Slovo\Graph $graph,
        \Aot\RussianMorphology\Slovo $slovo,
        $sentence_id,
        $position_slovo_in_sentence
    ) {
        return \Aot\Graph\Slovo\Vertex::create($graph, $slovo, $sentence_id, $position_slovo_in_sentence);
    }

    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public function buildGraph()
    {
        return \Aot\Graph\Slovo\Graph::create();
    }

    /**
     * Получение фабрики по id класса слова
     *
     * @param int $id_word_class
     * @return \Aot\RussianMorphology\FactoryBase
     */
    public function getFactorySlovo($id_word_class)
    {
        assert(is_int($id_word_class));

        if (empty($this->factories[$this->conformityPartsOfSpeech($id_word_class)])) {
            throw new \LogicException("Undefined part of speech = " . var_export($this->conformityPartsOfSpeech($id_word_class), 1));
        }

        return $this->factories[$this->conformityPartsOfSpeech($id_word_class)];
    }


    /**
     * Возвращаем соответствующий id части речи МИСОТа по id части речи АОТа
     *
     * @param int $id_part_of_speech_aot
     * @return int
     */
    public function conformityPartsOfSpeech($id_part_of_speech_aot)
    {
        assert(is_int($id_part_of_speech_aot));
        // соответвие id части речи из морфика и в мисоте
        $conformity = [
            \WrapperAot\ModelNew\Convert\Defines::VERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL,
            // гл
            \WrapperAot\ModelNew\Convert\Defines::NOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE,
            // сущ
            \WrapperAot\ModelNew\Convert\Defines::ADJECTIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE,
            // прил
            \WrapperAot\ModelNew\Convert\Defines::PRONOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE,
            // мест
            \WrapperAot\ModelNew\Convert\Defines::COMMUNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE,
            // прич
            \WrapperAot\ModelNew\Convert\Defines::PREPOSITION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG,
            // предлог
            # в МИСОТе нет
            # 7 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // аббревиатура
            \WrapperAot\ModelNew\Convert\Defines::UNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ,
            // союз
            \WrapperAot\ModelNew\Convert\Defines::PARTICLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA,
            // част
            \WrapperAot\ModelNew\Convert\Defines::INTERJECTION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE,
            // межд
            \WrapperAot\ModelNew\Convert\Defines::PARTICIPLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE,
            // деепр
            \WrapperAot\ModelNew\Convert\Defines::ADVERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE,
            // нар
            \WrapperAot\ModelNew\Convert\Defines::PREDICATIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE,
            // инф
            \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE,
            // числ
            # в МИСОТе нет
            # 15 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // сокращение
        ];

        return $conformity[$id_part_of_speech_aot];
    }

    /**
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param int $sentence_id
     * @param Link $link
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function buildSoyuzVertex(
        \Aot\Graph\Slovo\Graph $graph,
        $sentence_id,
        \Aot\Sviaz\Processors\AotGraph\Link $link
    ) {
        assert(is_int($sentence_id));
        $text_soyuz = $this->getTextOfSoyuz($link);
        $slovo_union = \Aot\RussianMorphology\ChastiRechi\Soyuz\Base::create($text_soyuz);
        return \Aot\Graph\Slovo\Vertex::createVertexWithoutPosition($graph, $slovo_union);
    }

    /**
     * @param Link $link
     * @return string
     * @throws \Exception
     */
    protected function getTextOfSoyuz(\Aot\Sviaz\Processors\AotGraph\Link $link)
    {
        $text_soyuz = \Aot\Sviaz\Processors\AotGraph\SubConjunctionRegistry::getSubConjunctionText($link);
        return $text_soyuz;
    }
}