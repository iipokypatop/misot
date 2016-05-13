<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 11/12/15
 * Time: 12:59
 */

namespace Aot\Sviaz\Processors\AotGraph;


class Builder
{
    /** @var \Aot\RussianMorphology\FactoryBase[]  */
    protected $factories;

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
        \Aot\Graph\Slovo\Edge::create(
            $main_vertex,
            $depended_vertex,
            $this->buildRule($main_vertex, $depended_vertex, $relation)
        );
    }


    /**
     * Собираем вершину
     * @param \Aot\Graph\Slovo\Graph $graph
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @param \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog
     * @return \Aot\Graph\Slovo\Vertex
     */
    public function buildVertex(\Aot\Graph\Slovo\Graph $graph, \Aot\RussianMorphology\Slovo $slovo, \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog = null)
    {
        return \Aot\Graph\Slovo\Vertex::create($graph, $slovo, $predlog);
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
            \DefinesAot::VERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL, // гл
            \DefinesAot::NOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE, // сущ
            \DefinesAot::ADJECTIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE, // прил
            \DefinesAot::PRONOUN_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE, // мест
            \DefinesAot::COMMUNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE, // прич
            \DefinesAot::PREPOSITION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG, // предлог
            # в МИСОТе нет
            # 7 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // аббревиатура
            \DefinesAot::UNION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ, // союз
            \DefinesAot::PARTICLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA, // част
            \DefinesAot::INTERJECTION_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE, // межд
            \DefinesAot::PARTICIPLE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE, // деепр
            \DefinesAot::ADVERB_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE, // нар
            \DefinesAot::PREDICATIVE_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE, // инф
            \DefinesAot::NUMERAL_CLASS_ID => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE, // числ
            # в МИСОТе нет
            # 15 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // сокращение
        ];

        return $conformity[$id_part_of_speech_aot];
    }
}