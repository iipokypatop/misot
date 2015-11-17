<?php

use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class VSOTest extends \AotTest\AotDataStorage
{
    protected $link_kw_member_id = []; // связь по слову в предложению и id мембера
    protected $sentence_array = [];


    public function testRunInClass()
    {
        $sequence = $this->getRawSequence();
        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
        $misot_to_aot->run($sequence, []);
        \Doctrine\Common\Util\Debug::dump($sequence->getSviazi(), 3);
    }

    /**
     *
     */
    public function testRun()
    {
        $sequence = $this->getRawSequence();

        assert(is_a($sequence, \Aot\Sviaz\Sequence::class, true));

        /** @var \Aot\Sviaz\SequenceMember\Base $member */
        $this->sentence_array = $this->getSentenceArrayBySequence($sequence);

        # восстановление предложения
        $sentence_string = join(' ', $this->sentence_array);

        $syntax_model = $this->getOriginalSyntaxModel($sentence_string);

        # создаём новую последовательность
        $new_sequence = $this->createNewSequence($sequence, $syntax_model);

        # заполняем её связями
        $this->fillRelations($new_sequence, $syntax_model);

        \Doctrine\Common\Util\Debug::dump($new_sequence, 4);
    }

    /**
     * Создаем правило
     * @param Sentence_space_SP_Rel $main_point - главная точка
     * @param Sentence_space_SP_Rel $depended_point - зависимая точка
     * @return \Aot\Sviaz\Rule\Base|false
     */
    private function createRule($main_point, $depended_point)
    {
        assert(is_a($main_point, Sentence_space_SP_Rel::class, true));
        assert(is_a($depended_point, Sentence_space_SP_Rel::class, true));

        $role_main = $role_dep = null;

        // конкретизируем роли главного и зависимого
        $this->roleSpecification($main_point, $role_main, $role_dep);

        if ($role_main === null || $role_dep === null) {
            return false;
        }

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
     * Конкретизация роли элемента
     * @param Sentence_space_SP_Rel $point - зависима или главная точка, не имеет значения
     * @param $role_main
     * @param $role_dep
     */
    protected function roleSpecification(Sentence_space_SP_Rel $point, &$role_main, &$role_dep)
    {
        assert(is_a($point, Sentence_space_SP_Rel::class, true));
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

    }

    /**
     * Создание связи в пространстве МИСОТ
     * @param \Aot\Sviaz\Sequence $seq - последовательность
     * @param array $pair_points - пара связанных точек
     * @return \Aot\Sviaz\Podchinitrelnaya\Base|false
     */
    private function createSvyaz($seq, array $pair_points)
    {
        assert(is_a($seq, \Aot\Sviaz\Sequence::class, true));
        $main_point = $depended_point = null;
        foreach ($pair_points as $key_point => $point) {
            if ($point->direction === 'x') {
                $main_point = $point;
            } else {
                $depended_point = $point;
            }
        }
        if ($main_point === null || $depended_point === null) {
            return false;
        }

        // заменяем обычный мембер на мембер с предлогом
        if ($main_point->O === DefinesAot::PREPOSITIONAL_PHRASE_MIVAR) {

            $this->replaceSequenceMember($seq, $main_point, $depended_point);
            return false;
        }


        $main = $seq[$this->link_kw_member_id[$main_point->kw]];
        $depended = $seq[$this->link_kw_member_id[$depended_point->kw]];
        $rule = $this->createRule($main_point, $depended_point);
        if ($rule === false) {
            return false;
        }
        return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $seq);
    }

    /**
     * Замена элемента в последовательности
     * @param \Aot\Sviaz\Sequence $seq
     * @param Sentence_space_SP_Rel $main_point
     * @param Sentence_space_SP_Rel $depended_point
     */
    protected function replaceSequenceMember(\Aot\Sviaz\Sequence $seq, Sentence_space_SP_Rel $main_point, Sentence_space_SP_Rel $depended_point)
    {
        assert(is_a($seq, \Aot\Sviaz\Sequence::class, true));
        assert(is_a($main_point, Sentence_space_SP_Rel::class, true));
        assert(is_a($depended_point, Sentence_space_SP_Rel::class, true));

        $factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
        $factory_main = $factories[$this->conformityPartsOfSpeech($main_point->dw->id_word_class)];
        $main_point->dw->word_form = $this->sentence_array[$main_point->kw];
        $prepose = $factory_main->get()->build($main_point->dw);

        $factory_depend = $factories[$this->conformityPartsOfSpeech($depended_point->dw->id_word_class)];
        $depended_point->dw->word_form = $this->sentence_array[$depended_point->kw];
        $slovo = $factory_depend->get()->build($depended_point->dw);

        $replaced_member_id = $this->link_kw_member_id[$depended_point->kw];
        $member_this_prepose = \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::create($slovo[0], $prepose[0]);
        $seq[$replaced_member_id] = $member_this_prepose;
    }

    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @return string[]
     */
    private function getSentenceArrayBySequence(\Aot\Sviaz\Sequence $sequence)
    {
        assert(is_a($sequence, \Aot\Sviaz\Sequence::class, true));
        $sentence_array = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
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
     * @return \Objects\Rule[]
     */
    private function getOriginalSyntaxModel($sentence_string)
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
     * @param array $syntax_model
     * @return \Aot\Sviaz\Sequence
     */
    protected function createNewSequence(\Aot\Sviaz\Sequence $old_sequence, array $syntax_model)
    {
        assert(is_array($syntax_model) && !empty($syntax_model));

        $factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
        $sorted_points = [];
        foreach ($syntax_model as $key => $point) {
            // приходит только по одному предложению => ks не нужен
            $sorted_points[$point->kw][$point->dw->id_word_class] = $key;
        }
        ksort($sorted_points);

        $new_sequence = \Aot\Sviaz\Sequence::create();

        foreach ($this->sentence_array as $key_word => $word_form) {
            // если нет точки для данного слова и она есть в старой последовательности, тогда берем её оттуда
            if (empty($sorted_points[$key_word]) && !empty($old_sequence[$key_word])) {
                $new_sequence[$key_word] = clone $old_sequence[$key_word];
                continue;
            }

            $items = $sorted_points[$key_word];
            $first_element_key = array_shift($items);
            $id_word_class = $syntax_model[$first_element_key]->dw->id_word_class;
            $factory = $factories[$this->conformityPartsOfSpeech($id_word_class)];
            $point = $syntax_model[$first_element_key];

            // берём форму слова из исходной последовательности
            $point->dw->word_form = $word_form;
            $slovo = $factory->get()->build($point->dw);
            $member = \Aot\Sviaz\SequenceMember\Word\Base::create($slovo[0]);

            // новый member
            $new_sequence[$key_word] = $member;

            // сохраняем связь между элементом в предложении и id мембера
            $this->link_kw_member_id[$key_word] = $new_sequence->getPosition($member);
        }

        return $new_sequence;
    }


    /**
     * Возвращаем соответствующий id части речи МИСОТа по id части речи АОТа
     * @param integer $id_part_of_speech_aot
     * @return integer
     */
    protected function conformityPartsOfSpeech($id_part_of_speech_aot)
    {
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

        assert(!empty($conformity[$id_part_of_speech_aot]));

        return $conformity[$id_part_of_speech_aot];
    }

    /**
     * Наполнение последовательности связями
     * @param \Aot\Sviaz\Sequence $sequence
     * @param array $syntax_model
     */
    protected function fillRelations(\Aot\Sviaz\Sequence $sequence, array $syntax_model)
    {
        assert(is_a($sequence, \Aot\Sviaz\Sequence::class, true));
        assert(!empty($syntax_model));

        $linked_pairs = [];
        foreach ($syntax_model as $key => $point) {
            $linked_pairs[$point->Oz][$key] = $point;
        }
        foreach ($linked_pairs as $id_pair => $pair_elements) {
            $sviaz = $this->createSvyaz($sequence, $pair_elements);
            if ($sviaz !== false) {
                $sequence->addSviaz($sviaz);
            }
        }
    }

}