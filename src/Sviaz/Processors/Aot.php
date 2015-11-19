<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 23/10/15
 * Time: 19:10
 */

namespace Aot\Sviaz\Processors;

use Aot\Sviaz\Role\Registry as RoleRegistry;
use DefinesAot;
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

        $rebuilded_sequence = $this->rebuild($new_sequence);
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

        $new_sequence = $this->builder->getNewSequence();

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
                $slovo = $factory->get()->build($point->dw);
                $member = $this->builder->getMemberBySlovo($slovo[0]);
            }

            // новый member
            $new_sequence[$key_word] = $member;

            // сохраняем связь между элементом в предложении и id мембера
            $this->link_kw_member_id[$key_word] = $new_sequence->getPosition($member);
        }

        return $new_sequence;
    }


    /**
     * Конкретизация роли элемента
     * @param \Sentence_space_SP_Rel $point - зависима или главная точка, не имеет значения
     * @return int[]
     */
    protected function roleSpecification(\Sentence_space_SP_Rel $point)
    {
        // подлежащее-сказуемое
        if ($point->O === DefinesAot::BASIS_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::OTNOSHENIE;
        } // составное сказуемое
        elseif ($point->O === DefinesAot::COMPLEX_PREDICATE_MIVAR) {
            # todo: создание двух связей?
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // косвенное дополнение
        elseif ($point->O === DefinesAot::INDIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // прямое дополнение
        elseif ($point->O === DefinesAot::DIRECT_OBJECT_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::VESCH;
        } // не + глагол
        elseif ($point->O === DefinesAot::NEGATIVE_NUMERAL_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // предлог + существительное
        elseif ($point->O === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
            # todo: создаём member c предлогом WordWithPreposition, по сути эта связь не нужна
//            $role_main = RoleRegistry::OTNOSHENIE;
//            $role_dep = RoleRegistry::VESCH;
        } // словосочетание (кресло директора)
        elseif ($point->O === DefinesAot::GENITIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } //  глагол и наречие
        elseif ($point->O === DefinesAot::ADVERB_PHRASE_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // крайне редко
        elseif ($point->O === DefinesAot::ADJECTIVE_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } // числительные
        elseif ($point->O === DefinesAot::NUMERAL_PHRASE_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($point->O === DefinesAot::ATTRIBUTE_NOUN_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        } elseif ($point->O === DefinesAot::ADJUNCT_VERB_MIVAR) {
            $role_main = RoleRegistry::OTNOSHENIE;
            $role_dep = RoleRegistry::SVOISTVO;
        } // дядя Ваня
        elseif ($point->O === DefinesAot::SEMANTIC_COORDINATION_MIVAR) {
            $role_main = RoleRegistry::VESCH;
            $role_dep = RoleRegistry::SVOISTVO;
        }

        if (!isset($role_main) || !isset($role_dep)) {
            return [];
        }

        return [$role_main, $role_dep];
    }

    /**
     * Создание связи в пространстве МИСОТ
     * @param \Aot\Sviaz\Sequence $sequence - последовательность
     * @param \Sentence_space_SP_Rel[] $pair_points - пара связанных точек
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    protected function createSvyaz(\Aot\Sviaz\Sequence $sequence, array $pair_points)
    {
        $main_point = $depended_point = null;
        foreach ($pair_points as $key_point => $point) {
            if ($point->direction === 'x') {
                $main_point = $point;
            } else {
                $depended_point = $point;
            }
        }
        if ($main_point === null || $depended_point === null) {
            return null;
        }

        // заменяем обычный мембер на мембер с предлогом
        if ($main_point->O === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {

            $this->replaceSequenceMember($sequence, $main_point, $depended_point);
            return null;
        }

        $main = $sequence[$this->link_kw_member_id[$main_point->kw]];
        $depended = $sequence[$this->link_kw_member_id[$depended_point->kw]];

        // конкретизируем роли главного и зависимого
        $roles = $this->roleSpecification($main_point);

        if (!empty($roles)) {
            $rule = $this->builder->createRule($main_point, $depended_point, $roles);
            return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $sequence);
        }
        return null;

    }


    /**
     * Замена элемента в последовательности
     * @param \Aot\Sviaz\Sequence $seq
     * @param \Sentence_space_SP_Rel $main_point
     * @param \Sentence_space_SP_Rel $depended_point
     */
    protected function replaceSequenceMember(\Aot\Sviaz\Sequence $seq, Sentence_space_SP_Rel $main_point, Sentence_space_SP_Rel $depended_point)
    {
        $factory_main = $this->builder->getFactory($main_point->dw->id_word_class);
        $main_point->dw->word_form = $this->sentence_array[$main_point->kw];
        $prepose = $factory_main->get()->build($main_point->dw);

        $factory_depend = $this->builder->getFactory($depended_point->dw->id_word_class);
        $depended_point->dw->word_form = $this->sentence_array[$depended_point->kw];
        $slovo = $factory_depend->get()->build($depended_point->dw);

        $replaced_member_id = $this->link_kw_member_id[$depended_point->kw];
        $member_with_prepose = \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::create($slovo[0], $prepose[0]);
        $seq[$replaced_member_id] = $member_with_prepose;
        #TODO: нужно убрать еще и $seq[$replaced_member_id-1] ??
        if (isset($seq[$replaced_member_id - 1])) {
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
        assert(!empty($syntax_model));
        foreach ($syntax_model as $point) {
            assert(is_a($point, \Sentence_space_SP_Rel::class, true));
        }


        $linked_pairs = $this->getLinkedPairs($syntax_model);

        if (empty($linked_pairs)) {
            return;
        }

        foreach ($linked_pairs as $id_pair => $pair_points) {

            $defined_pair = $this->getMainAndDependPoints($pair_points);
            if (empty($defined_pair)) {
                continue;
            }

            list($main_point, $depended_point) = $defined_pair;

            // заменяем обычный мембер на мембер с предлогом
            if ($main_point->O === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {
                $this->replaceSequenceMember($sequence, $main_point, $depended_point);
                continue;
            }

            $main = $sequence[$this->link_kw_member_id[$main_point->kw]];
            $depended = $sequence[$this->link_kw_member_id[$depended_point->kw]];
            // конкретизируем роли главного и зависимого
            $roles = $this->roleSpecification($main_point);

            if (!empty($roles)) {
                $rule = $this->builder->createRule($main_point, $depended_point, $roles);
                $sviaz = $this->builder->createSviaz($main, $depended, $rule, $sequence);
                $sequence->addSviaz($sviaz);
            }
        }
    }

    /**
     * Получаем главную и зависимую точку из пары
     * @param \Sentence_space_SP_Rel[] $pair_points
     * @return \Sentence_space_SP_Rel[]
     */
    protected function getMainAndDependPoints(array $pair_points)
    {
        $main_point = $depended_point = null;
        foreach ($pair_points as $key_point => $point) {
            if ($point->direction === 'x') {
                $main_point = $point;
            } else {
                $depended_point = $point;
            }
        }
        if ($main_point === null || $depended_point === null) {
            return [];
        }

        return [$main_point, $depended_point];

    }

    /**
     * Собираем новую последовательность
     * @param \Aot\Sviaz\Sequence $new_sequence
     * @return \Aot\Sviaz\Sequence
     */
    protected function rebuild(\Aot\Sviaz\Sequence $new_sequence)
    {
        $rebuilding_sequence = \Aot\Sviaz\Sequence::create();

        # переносим мемберы
        foreach ($new_sequence as $member) {
            $rebuilding_sequence->append($member);
        }

        # переносим связи
        foreach ($new_sequence->getSviazi() as $sviaz) {
            $rebuilding_sequence->addSviaz($sviaz);
        }

        return $rebuilding_sequence;
    }

    /**
     * @param \Sentence_space_SP_Rel[] $syntax_model
     * @return \Sentence_space_SP_Rel[]
     */
    private function getLinkedPairs(array $syntax_model)
    {
        $linked_pairs = [];
        foreach ($syntax_model as $key => $point) {
            $linked_pairs[$point->Oz][$key] = $point;
        }
        return $linked_pairs;
    }

}