<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 23/10/15
 * Time: 19:10
 */

namespace Aot\Sviaz\Processors;

use Sentence_space_SP_Rel;


class Aot extends Base
{
    protected $link_kw_member_id = []; // связь по слову в предложению и id мембера
    protected $sentence_array = [];
    /** @var \Aot\Sviaz\Processors\BuilderAot */
    protected $builder;

    protected function __construct()
    {
        $this->builder = \Aot\Sviaz\Processors\BuilderAot::create();
    }

    /**
     * Создаём новую последовательность
     * @param \Aot\Sviaz\Sequence $sequence
     * @param array $rules
     * @return \Aot\Sviaz\Sequence $new_sequence
     */
    public function run(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        $this->link_kw_member_id = [];
        $this->sentence_array = [];

        /** @var \Aot\Sviaz\SequenceMember\Base $member */
        $this->sentence_array = $this->getSentenceWordsBySequence($sequence);

        # восстановление предложения
        $sentence_string = join(' ', $this->sentence_array);

        $syntax_model = $this->getOriginalSyntaxModel($sentence_string);

        # создаём новую последовательность
        $new_sequence = $this->createNewSequence($sequence, $syntax_model);

        # заполняем её связями
        $this->fillRelations($new_sequence, $syntax_model);

        # пересобираем последовательность
        $rebuilded_sequence = $this->builder->rebuildSequence($new_sequence);

        return $rebuilded_sequence;
    }

    /**
     * Формируем массив слов предложения
     * @param \Aot\Sviaz\Sequence $sequence
     * @return string[]
     */
    protected function getSentenceWordsBySequence(\Aot\Sviaz\Sequence $sequence)
    {
        $sentence_array = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                # пропускаем, поскольку АОТ игнорирует знаки препинания
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition) {
                /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                $sentence_array[] = $member->getPredlog()->getText();
                $sentence_array[] = $member->getSlovo()->getText();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
                $sentence_array[] = $member->getSlovo()->getText();
            }
        }
        return $sentence_array;
    }

    /**
     * Получение синтаксической модели через АОТ
     * @param string $sentence_string
     * @return \Sentence_space_SP_Rel[]
     */
    protected function getOriginalSyntaxModel($sentence_string)
    {
        assert(is_string($sentence_string));

        $mivar = new \DMivarText(['txt' => $sentence_string]);

        $mivar->syntax_model();

        $result = $mivar->getSyntaxModel();

        return !empty($result) ? $result : [];

    }

    /**
     * Создание новой последовательности
     * @param \Aot\Sviaz\Sequence $old_sequence
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @return \Aot\Sviaz\Sequence
     */
    protected function createNewSequence(\Aot\Sviaz\Sequence $old_sequence, array $syntax_model)
    {
        assert(is_array($syntax_model) && !empty($syntax_model));

        $sorted_points = [];
        foreach ($syntax_model as $key => $point) {
            // приходит только по одному предложению => ks не нужен
            $sorted_points[$point->kw][$point->dw->id_word_class] = $key;
        }
        ksort($sorted_points);

        $new_sequence = $this->builder->createNewSequence();

        foreach ($this->sentence_array as $key_word => $word_form) {
            // если нет точки для данного слова и она есть в старой последовательности, тогда берем её оттуда
            if (empty($sorted_points[$key_word]) && !empty($old_sequence[$key_word])) {
                $new_sequence[$key_word] = clone $old_sequence[$key_word];
                continue;
            }

            $items = $sorted_points[$key_word];
            $first_element_key = array_shift($items);
            $id_word_class = $syntax_model[$first_element_key]->dw->id_word_class;
            $factory = $this->builder->getFactory($id_word_class);
            if ($factory === null) {
                $member = clone $old_sequence[$key_word];
            } else {
                $point = $syntax_model[$first_element_key];
                // берём форму слова из исходной последовательности
                $point->dw->word_form = $word_form;
                $slovo = $factory->build($point->dw);
                $member = $this->builder->createMemberBySlovo($slovo[0]);
            }

            // новый member
            $new_sequence[$key_word] = $member;

            // сохраняем связь между элементом в предложении и id мембера
            $this->link_kw_member_id[$key_word] = $new_sequence->getPosition($member);
        }

        return $new_sequence;
    }

    /**
     * Замена элемента в последовательности без предлога на элемент с предлогом
     * @param \Aot\Sviaz\Sequence $seq
     * @param \Sentence_space_SP_Rel $prepose_point
     * @param \Sentence_space_SP_Rel $word_point
     */
    protected function replaceSequenceMemberToMemberWithPreposition(\Aot\Sviaz\Sequence $seq, Sentence_space_SP_Rel $prepose_point, Sentence_space_SP_Rel $word_point)
    {
        $factory_main = $this->builder->getFactory($prepose_point->dw->id_word_class);
        $prepose_point->dw->word_form = $this->sentence_array[$prepose_point->kw];
        $prepose = $factory_main->build($prepose_point->dw);

        $factory_depend = $this->builder->getFactory($word_point->dw->id_word_class);
        $word_point->dw->word_form = $this->sentence_array[$word_point->kw];
        $slovo = $factory_depend->build($word_point->dw);

        $replaced_member_id = $this->link_kw_member_id[$word_point->kw];
        $member_with_prepose = \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::create($slovo[0], $prepose[0]);
        $seq[$replaced_member_id] = $member_with_prepose;

        // убираем предудыщий элемент, если он есть и является предлогом
        /** @var \Aot\Sviaz\SequenceMember\Word\Base[] $seq */
        if (isset($seq[$replaced_member_id - 1]) && is_a($seq[$replaced_member_id - 1]->getSlovo(), \Aot\RussianMorphology\ChastiRechi\Predlog\Base::class, true)) {
            unset($seq[$replaced_member_id - 1]);
        }
    }


    /**
     * Наполнение последовательности связями
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @return void
     */
    protected function fillRelations(\Aot\Sviaz\Sequence $sequence, array $syntax_model)
    {

        if (empty($syntax_model)) {
            return;
        }

        foreach ($syntax_model as $point) {
            assert(is_a($point, \Sentence_space_SP_Rel::class, true));
        }


        $linked_pairs = $this->getLinkedPairs($syntax_model);

        if (empty($linked_pairs)) {
            return;
        }

        foreach ($linked_pairs as $pair_points) {


            $pair_points = array_values($pair_points);

            $main_id = $this->getIdMainPoint($pair_points[0], $pair_points[1]);
            $depend_id = ($main_id === 0) ? 1 : 0;


            // заменяем обычный мембер на мембер с предлогом
            if ($pair_points[$main_id]->O === \DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
                $this->replaceSequenceMemberToMemberWithPreposition($sequence, $pair_points[$main_id], $pair_points[$depend_id]);
                continue;
            }

            if (empty($sequence[$this->link_kw_member_id[$pair_points[$main_id]->kw]])) {
                throw new \LogicException('The sequence does not have a member with id = ' . $pair_points[$main_id]->kw);
            }

            if (empty($sequence[$this->link_kw_member_id[$pair_points[$depend_id]->kw]])) {
                throw new \LogicException('The sequence does not have a member with id = ' . $pair_points[$depend_id]->kw);
            }

            $main = $sequence[$this->link_kw_member_id[$pair_points[$main_id]->kw]];
            $depended = $sequence[$this->link_kw_member_id[$pair_points[$depend_id]->kw]];

            // конкретизируем роли главного и зависимого
            $roles = \Aot\Sviaz\Processors\RoleSpecification::getRoles($pair_points[$main_id]->O);

            $main_point_part_of_speech = $this->builder->conformityPartsOfSpeech($pair_points[$main_id]->dw->id_word_class);
            $depended_point_part_of_speech = $this->builder->conformityPartsOfSpeech($pair_points[$depend_id]->dw->id_word_class);
            list($main_role, $depended_role) = $roles;
            $rule = $this->builder->createRule($main_point_part_of_speech, $depended_point_part_of_speech, $main_role, $depended_role);
            $sviaz = $this->builder->createSviaz($main, $depended, $rule, $sequence);
            $sequence->addSviaz($sviaz);

        }
    }

    /**
     * Получаем id главной точки из пары
     *
     * @param \Sentence_space_SP_Rel $first
     * @param \Sentence_space_SP_Rel $second
     * @return int
     * @throws \LogicException
     */
    protected function getIdMainPoint(\Sentence_space_SP_Rel $first, \Sentence_space_SP_Rel $second)
    {
        if ($first->direction === 'x') {
            return 0;
        } elseif ($second->direction === 'x') {
            return 1;
        } else {
            throw new \LogicException('The pair of elements does not have valid main element');
        }
    }

    /**
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @return \Sentence_space_SP_Rel[]
     */
    protected function getLinkedPairs(array $syntax_model)
    {
        $linked_pairs = [];
        foreach ($syntax_model as $key => $point) {
            $linked_pairs[$point->Oz][$key] = $point;
        }
        return $linked_pairs;
    }

}