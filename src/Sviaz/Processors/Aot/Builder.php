<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 18/11/15
 * Time: 19:15
 */

namespace Aot\Sviaz\Processors\Aot;

use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

/**
 * Билдер для процессора Aot
 * Class Builder
 */
class Builder
{
    /** @var \Aot\RussianMorphology\Factory[] */
    protected $factories;

    /**
     * @return \Aot\Sviaz\Processors\Aot\Builder
     */
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
     *
     * @param int $id_word_class
     * @return \Aot\RussianMorphology\FactoryBase
     */
    public function getFactory($id_word_class)
    {
        assert(is_int($id_word_class));

        if (empty($this->factories[$this->conformityPartsOfSpeech($id_word_class)])) {
            throw new \LogicException("Undefined part of speech = " . var_export($this->conformityPartsOfSpeech($id_word_class), 1));
        }

        return $this->factories[$this->conformityPartsOfSpeech($id_word_class)];
    }

    /**
     * Создание новой последовательности
     *
     * @return \Aot\Sviaz\Sequence
     */
    public function createSequence()
    {
        return \Aot\Sviaz\Sequence::create();
    }


    /**
     * Создание мембера по слову
     *
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Sviaz\SequenceMember\Word\Base
     */
    public function createMemberBySlovo(\Aot\RussianMorphology\Slovo $slovo)
    {
        return \Aot\Sviaz\SequenceMember\Word\Base::create($slovo);
    }

    /**
     * @param \Aot\RussianMorphology\Slovo $slovo
     * @return \Aot\Sviaz\SequenceMember\Word\Base
     */
    public function createSlovoByDw(\Aot\RussianMorphology\Slovo $slovo)
    {
        return \Aot\Sviaz\SequenceMember\Word\Base::create($slovo);
    }

    /**
     * Создаем правило
     *
     * @param int $main_point_part_of_speech - главная точка
     * @param int $depended_point_part_of_speech - зависимая точка
     * @param int $main_role
     * @param int $depended_role
     * @return \Aot\Sviaz\Rule\Base
     */
    public function createRule($main_point_part_of_speech, $depended_point_part_of_speech, $main_role, $depended_role)
    {
        assert(is_int($main_point_part_of_speech));
        assert(is_int($depended_point_part_of_speech));
        assert(is_int($main_role));
        assert(is_int($depended_role));
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        $main_point_part_of_speech,
                        $main_role
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        $depended_point_part_of_speech,
                        $depended_role
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
        \Aot\Sviaz\Sequence $sequence
    )
    {
        return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $sequence);
    }


    /**
     * Возвращаем соответствующий id части речи МИСОТа по id части речи АОТа
     *
     * @param integer $id_part_of_speech_aot
     * @return integer
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
     * Собираем новую последовательность
     * @param \Aot\Sviaz\Sequence $sequence
     * @return \Aot\Sviaz\Sequence
     */
    public function rebuildSequence(\Aot\Sviaz\Sequence $sequence)
    {
        $rebuilding_sequence = \Aot\Sviaz\Sequence::create();

        # переносим мемберы
        foreach ($sequence as $member) {
            $rebuilding_sequence->append($member);
        }

        # пересоздаём
        foreach ($sequence->getSviazi() as $sviaz) {
            $main = $sviaz->getMainSequenceMember();
            $depend = $sviaz->getDependedSequenceMember();
            $rule = $sviaz->getRule();
            $rebuilded_sviaz = $this->createSviaz($main, $depend, $rule, $rebuilding_sequence);
            $rebuilding_sequence->addSviaz($rebuilded_sviaz);
        }

        return $rebuilding_sequence;
    }
}