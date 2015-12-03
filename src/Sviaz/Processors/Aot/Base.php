<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 23/10/15
 * Time: 19:10
 */

namespace Aot\Sviaz\Processors\Aot;


class Base extends \Aot\Sviaz\Processors\Base
{
    const MAIN_POINT = 'x';
    const DEPENDED_POINT = 'y';


    /** @var \Aot\Sviaz\Processors\Aot\Builder */
    protected $builder;

    /** @var \Aot\Sviaz\Processors\Aot\OffsetManager */
    protected $offsetManager;


    protected function __construct()
    {
        $this->builder = Builder::create();
    }

    /**
     * Создаём новую последовательность
     * @param \Aot\Sviaz\Sequence $sequence
     * @param array $rules
     * @return \Aot\Sviaz\Sequence $new_sequence
     */
    public function run(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        $sequence_driver = Sequence\SequenceDriver::create($sequence);

        if ($sequence_driver->isModelEmpty()) {
            return $this->builder->rebuildSequence($sequence);
        }

        $new_sequence = $this->runSyntaxProcessor($sequence, $sequence_driver);

        return $this->builder->rebuildSequence($new_sequence);
    }


    /**
     * @param \Aot\Sviaz\Sequence $old_sequence
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     * @return \Aot\Sviaz\Sequence
     */
    protected function runSyntaxProcessor(\Aot\Sviaz\Sequence $old_sequence, Sequence\SequenceDriver $sequence_driver)
    {
        $new_sequence = $this->createNewSequence($old_sequence, $sequence_driver);

        list($to_replace, $to_create_relations) = $this->splitLinkedPairs($sequence_driver);

        $this->joinMembersWithPreposition($new_sequence, $sequence_driver, $to_replace);

        if (!empty($to_create_relations)) {
            $this->fillRelations($new_sequence, $sequence_driver, $to_create_relations);
        }

        return $new_sequence;
    }


    /**
     * Создание новой последовательности
     * @param \Aot\Sviaz\Sequence $old_sequence
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     * @return \Aot\Sviaz\Sequence
     */
    protected function createNewSequence(\Aot\Sviaz\Sequence $old_sequence, Sequence\SequenceDriver $sequence_driver)
    {
        $syntax_model = $sequence_driver->getSyntaxModel();
        $sentence_words_array = $sequence_driver->getWordsArray();
        $offsetManager = $sequence_driver->getOffsetManager();

        foreach ($syntax_model as $point) {
            assert(is_a($point, \Sentence_space_SP_Rel::class, true));
        }

        $sorted_points = [];
        foreach ($syntax_model as $key => $point) {
            // приходит только по одному предложению => ks не нужен
            $sorted_points[$point->kw][$point->dw->id_word_class] = $key;
        }

        $new_sequence = $this->builder->createSequence();


        foreach ($sentence_words_array as $key_word => $word_form) {
            // если нет точки для данного слова и она есть в старой последовательности, тогда берем её оттуда и есть в последовательности

            $condition = empty($offsetManager->nonexistent_misot[$key_word])
                &&
                (
                    !empty($offsetManager->nonexistent_aot[$key_word])
                    ||
                    empty($sorted_points[$offsetManager->getAotKeyBySentenceWordKey($key_word)])
                );

            if ($condition) {
                if (!empty($old_sequence[$offsetManager->getMisotKeyBySentenceWordKey($key_word)])) {
                    $new_sequence[$key_word] =
                        clone $old_sequence[$offsetManager->getMisotKeyBySentenceWordKey($key_word)];
                } else {
                    throw new \LogicException('key ' . var_export($key_word, 1) . ' must be defined');
                }
                continue;
            }

            $items = $sorted_points[$offsetManager->getAotKeyBySentenceWordKey($key_word)];

            $syntax_key = null;

            if (empty($offsetManager->nonexistent_misot[$key_word])) {
                $misot_key = $offsetManager->getMisotKeyBySentenceWordKey($key_word);
                $initial_form_from_member = $old_sequence[$misot_key]->getSlovo()->getInitialForm();
                foreach ($items as $id_word_class => $key) {
                    if ($syntax_model[$key]->dw->initial_form === $initial_form_from_member) {
                        $syntax_key = $key;
                        break;
                    }
                }
            }

            if ($syntax_key === null) {
                $syntax_key = array_shift($items);
            }

            $id_word_class = $syntax_model[$syntax_key]->dw->id_word_class;
            $factory = $this->builder->getFactory($id_word_class);

            $point = $syntax_model[$syntax_key];

            // берём форму слова из исходной последовательности
            $point->dw->word_form = $word_form;
            $slovo = $factory->build($point->dw);
            $new_sequence[$key_word] = $this->builder->createMemberBySlovo($slovo[0]);
        }

        return $new_sequence;
    }

    /**
     * Замена элемента в последовательности без предлога на элемент с предлогом
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Sentence_space_SP_Rel $prepose_point
     * @param \Sentence_space_SP_Rel $word_point
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     */
    protected function replaceSequenceMemberToMemberWithPreposition(
        \Aot\Sviaz\Sequence $sequence,
        \Sentence_space_SP_Rel $prepose_point,
        \Sentence_space_SP_Rel $word_point,
        Sequence\SequenceDriver $sequence_driver
    )
    {
        $sentence_words_array = $sequence_driver->getWordsArray();
        $offsetManager = $sequence_driver->getOffsetManager();

        if ($prepose_point->dw->id_word_class !== \DefinesAot::PREPOSITION_CLASS_ID
            || !in_array($word_point->dw->id_word_class, [\DefinesAot::NOUN_CLASS_ID, \DefinesAot::PRONOUN_CLASS_ID], true)
        ) {
            // данная ситуация - бага в аоте/конвертере из аота
            return;
        }

        $factory_main = $this->builder->getFactory($prepose_point->dw->id_word_class);
        $prepose_point->dw->word_form =
            $sentence_words_array[$offsetManager->getSentenceWordKeyByAotKey($prepose_point->kw)];
        $prepose = $factory_main->build($prepose_point->dw);

        $factory_depend = $this->builder->getFactory($word_point->dw->id_word_class);
        $word_point->dw->word_form =
            $sentence_words_array[$offsetManager->getSentenceWordKeyByAotKey($word_point->kw)];
        $slovo = $factory_depend->build($word_point->dw);

        $replaced_member_id = $offsetManager->getSentenceWordKeyByAotKey($word_point->kw);

        $member_with_prepose = \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::create($slovo[0], $prepose[0]);
        $sequence[$replaced_member_id] = $member_with_prepose;

        // убираем предудыщий элемент, если он есть и является предлогом
        /** @var \Aot\Sviaz\SequenceMember\Word\Base[] $sequence */
        if (isset($sequence[$replaced_member_id - 1])
            && is_a($sequence[$replaced_member_id - 1]->getSlovo(), \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class, true)
        ) {
            unset($sequence[$replaced_member_id - 1]);
        }
    }


    /**
     * Наполнение последовательности связями
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     * @param \Sentence_space_SP_Rel[][] $linked_pairs
     * @return void
     */
    protected function fillRelations(\Aot\Sviaz\Sequence $sequence, Sequence\SequenceDriver $sequence_driver, array $linked_pairs)
    {
        if (empty($linked_pairs)) {
            return;
        }

        $offsetManager = $sequence_driver->getOffsetManager();

        foreach ($linked_pairs as $pair_points) {

            $main_id = $offsetManager->getSentenceWordKeyByAotKey($pair_points[self::MAIN_POINT]->kw);
            if (empty($sequence[$main_id])
            ) {
                throw new \LogicException('The sequence does not have a member with id = ' . $pair_points[self::MAIN_POINT]->kw);
            }
            $main = $sequence[$main_id];

            $depended_id = $offsetManager->getSentenceWordKeyByAotKey($pair_points[self::DEPENDED_POINT]->kw);
            if (empty($sequence[$depended_id])) {
                throw new \LogicException('The sequence does not have a member with id = ' . $pair_points[self::DEPENDED_POINT]->kw);
            }

            $depended = $sequence[$depended_id];

            // конкретизируем роли главного и зависимого
            $roles = \Aot\Sviaz\Processors\Aot\RoleSpecificator::getRoles($pair_points[self::MAIN_POINT]->O);

            $main_point_part_of_speech = $this->builder->conformityPartsOfSpeech($pair_points[self::MAIN_POINT]->dw->id_word_class);
            $depended_point_part_of_speech = $this->builder->conformityPartsOfSpeech($pair_points[self::DEPENDED_POINT]->dw->id_word_class);
            list($main_role, $depended_role) = $roles;
            $rule = $this->builder->createRule($main_point_part_of_speech, $depended_point_part_of_speech, $main_role, $depended_role);
            $sviaz = $this->builder->createSviaz($main, $depended, $rule, $sequence);
            $sequence->addSviaz($sviaz);
        }
    }


    /**
     * Получение связанных пар из синтаксической модели
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     * @return \Sentence_space_SP_Rel[][][]
     */
    protected function splitLinkedPairs(Sequence\SequenceDriver $sequence_driver)
    {
        $linked_pairs = $sequence_driver->getLinkedPairs();

        $to_replace = [];
        $to_create_relations = [];

        foreach ($linked_pairs as $pair_points) {
            if ($pair_points[self::MAIN_POINT]->O === \DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
                $to_replace[] = $pair_points;
            } else {
                $to_create_relations[] = $pair_points;
            }
        }

        return [$to_replace, $to_create_relations];
    }


    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Aot\Sviaz\Processors\Aot\Sequence\SequenceDriver $sequence_driver
     * @param \Sentence_space_SP_Rel[][] $linked_pairs
     */
    protected function joinMembersWithPreposition(\Aot\Sviaz\Sequence $sequence, $sequence_driver, $linked_pairs)
    {
        // заменяем обычный мембер на мембер с предлогом
        foreach ($linked_pairs as $pair_points) {
            $this->replaceSequenceMemberToMemberWithPreposition(
                $sequence,
                $pair_points[self::MAIN_POINT],
                $pair_points[self::DEPENDED_POINT],
                $sequence_driver
            );
        }
    }
}