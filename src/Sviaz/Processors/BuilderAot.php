<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 18/11/15
 * Time: 19:15
 */

namespace Aot\Sviaz\Processors;

use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

/**
 * Билдер для процессора Aot
 * Class Builder
 * @package Aot\Sviaz\Processors
 */
class BuilderAot
{
    /** @var \Aot\RussianMorphology\Factory[] */
    protected $factories;

    public static function create()
    {
        return new static();
    }

    /**
     * Builder constructor.
     */
    protected function __construct()
    {
        $this->factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
    }

    /**
     * Получение фабрики по id класса слова
     * @param int $id_word_class
     * @return \Aot\RussianMorphology\Factory
     */
    public function getFactory($id_word_class)
    {
        assert(is_int($id_word_class));
        return $this->factories[$this->conformityPartsOfSpeech($id_word_class)];
    }

    /**
     * @return \Aot\Sviaz\Sequence
     */
    public function getNewSequence()
    {
        return \Aot\Sviaz\Sequence::create();
    }


    /**
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Sviaz\SequenceMember\Word\Base
     */
    public function getMemberBySlovo(\Aot\RussianMorphology\Slovo $slovo)
    {
        return \Aot\Sviaz\SequenceMember\Word\Base::create($slovo);
    }

    /**
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Sviaz\SequenceMember\Word\Base
     */
    public function getSlovoByDw(\Aot\RussianMorphology\Slovo $slovo)
    {
        return \Aot\Sviaz\SequenceMember\Word\Base::create($slovo);
    }

    /**
     * Создаем правило
     * @param \Sentence_space_SP_Rel $main_point - главная точка
     * @param \Sentence_space_SP_Rel $depended_point - зависимая точка
     * @param array $roles
     * @return \Aot\Sviaz\Rule\Base
     */
    public function createRule(\Sentence_space_SP_Rel $main_point, \Sentence_space_SP_Rel $depended_point, array $roles)
    {
        assert(count($roles) === 2);

        list($role_main, $role_dep) = $roles;

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        $this->conformityPartsOfSpeech($main_point->dw->id_word_class),
                        $role_main
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        $this->conformityPartsOfSpeech($depended_point->dw->id_word_class),
                        $role_dep
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );
        return $builder->get();
    }

    /**
     * Создание связи в пространстве МИСОТ
     *
     * @param \Aot\Sviaz\SequenceMember\Word\Base $main
     * @param \Aot\Sviaz\SequenceMember\Word\Base $depended
     * @param \Aot\Sviaz\Rule\Base $rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    public function createSviaz(
        \Aot\Sviaz\SequenceMember\Word\Base $main,
        \Aot\Sviaz\SequenceMember\Word\Base $depended,
        \Aot\Sviaz\Rule\Base $rule,
        \Aot\Sviaz\Sequence $sequence)
    {
        return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $sequence);
    }


    /**
     * Возвращаем соответствующий id части речи МИСОТа по id части речи АОТа
     * @param integer $id_part_of_speech_aot
     * @return integer
     */
    protected function conformityPartsOfSpeech($id_part_of_speech_aot)
    {
        assert(is_int($id_part_of_speech_aot));
        // соответвие id части речи из морфика и в мисоте
        $conformity = [
            1 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::GLAGOL, // гл
            2 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SUSCHESTVITELNOE, // сущ
            3 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRILAGATELNOE, // прил
            4 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MESTOIMENIE, // мест
            5 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PRICHASTIE, // прич
            6 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::PREDLOG, // предлог
            # в МИСОТе нет
            # 7 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // аббревиатура
            8 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::SOYUZ, // союз
            9 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHASTICA, // част
            10 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::MEZHDOMETIE, // межд
            11 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::DEEPRICHASTIE, // деепр
            12 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::NARECHIE, // нар
            13 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::INFINITIVE, // инф
            14 => \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::CHISLITELNOE, // числ
            # в МИСОТе нет
            # 15 =>\Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::, // сокращение
        ];

        return $conformity[$id_part_of_speech_aot];
    }
}