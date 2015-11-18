<?php


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry;

class VSOTest extends \AotTest\AotDataStorage
{
    protected $cache_nf_member = [];
    protected $cache_hash_member_z = []; // соответствие member == uuid_z
    protected $cache_z_hash_member = []; // соответствие uuid_z == member
    protected $cache_z_hash_member2 = []; // соответствие uuid_z == member


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
        $sentence_array = $this->getSentenceArrayBySequence($sequence);
        $sentence_string = join(' ', $sentence_array);


//        die();
        $syntax_model = $this->getOriginalSyntaxModel($sentence_string);
//        print_r($syntax_model);
        $factories = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getFactories();
//        print_r($factories);
        $sorted_points = [];
        foreach ($syntax_model as $key => $point) {
            // приходит только по одному предложению => ks не нужен
            $sorted_points[$point->kw][$point->dw->id_word_class] = $key;
        }
        ksort($sorted_points);
//        print_r($cache);
        # todo создать новую последовательность с новыми мемберами

        $new_sequence = \Aot\Sviaz\Sequence::create();

        // соответвие id части речи из морфика и в мисоте
        $conformity = [
            1 => 12,
            2 => 10,
            3 => 11,
            5 => 14,
            6 => 19,
            9 => 21,
        ];
        foreach ($sorted_points as $key_word => $items) {
            $first_element_key = array_shift($items);
//            print_r([$syntax_model[$first_element_key]->dw->id_word_class => $syntax_model[$first_element_key]->dw->name_word_class]);
            $id_word_class = $syntax_model[$first_element_key]->dw->id_word_class;
            $factory = $factories[$conformity[$id_word_class]];
            $point_dw = $syntax_model[$first_element_key]->dw;
            // берём форму слова из исходной последовательности
            $point_dw->word_form = 'лала';
            $member = $factory->get()->build($point_dw);
            // новый member
            $sequence->append($member);
        }

        die();
        # заполнить её связями

        $linked_pairs = [];
        foreach ($syntax_model as $key => $point) {
            $linked_pairs[$point->Oz][$key] = $point;
        }
        print_r($linked_pairs);
        die();

        $sorted_vso = $this->sortVSO($vso);
//        \Doctrine\Common\Util\Debug::dump($vso, 3);
//        \Doctrine\Common\Util\Debug::dump($sorted_vso, 3);

//        \Doctrine\Common\Util\Debug::dump($sentence_string, 3);
//        \Doctrine\Common\Util\Debug::dump($this->cache_nf_member, 3);
//        \Doctrine\Common\Util\Debug::dump($vso, 3);

        foreach ($sorted_vso as $type_rule => $rules) {
            /** @var \Objects\Rule $rule */
            foreach ($rules as $rule) {
//            \Doctrine\Common\Util\Debug::dump($rule, 3);
                $sviaz = null;
                if ($type_rule === 'VO') {
                    $sviaz = $this->createSvyaz($sequence, $rule, 'V', 'O');
                    if ($sviaz !== false) {
                        $sequence->addSviaz($sviaz);
                    }
                } elseif ($type_rule === 'OV') {
                    $sviaz = $this->createSvyaz($sequence, $rule, 'O', 'V');
                    if ($sviaz !== false) {
                        $sequence->addSviaz($sviaz);
                    }
                } elseif ($type_rule === 'VS') {
                    $sviaz = $this->createSvyaz($sequence, $rule, 'V', 'SV');
                    if ($sviaz !== false) {
                        $sequence->addSviaz($sviaz);
                    }
                } elseif ($type_rule === 'OS') {
                    $sviaz = $this->createSvyaz($sequence, $rule, 'O', 'SO');
                    if ($sviaz !== false) {
                        $sequence->addSviaz($sviaz);
                    }
                }

//                \Doctrine\Common\Util\Debug::dump(['rule' => $rule, '$sviaz' => $sviaz], 3);
            }
        }

//        \Doctrine\Common\Util\Debug::dump($vso, 3);
//        \Doctrine\Common\Util\Debug::dump($sequence->getSviazi(), 3);
//        \Doctrine\Common\Util\Debug::dump($this->cache_z_hash_member2, 3);
    }

    /**
     * Сортируем ВСО
     * @param \Objects\Rule[] $vso
     * @return array
     */
    private function sortVSO(array $vso)
    {
        $sorted_vso = [];
        foreach ($vso as $key_rule => $rule) {
//            \Doctrine\Common\Util\Debug::dump($rule, 3);
            if ($rule->get_name_V() !== null && !empty($this->cache_nf_member[$rule->get_name_V()])) {

                if ($rule->get_name_O() !== null /*&& !empty($this->cache_nf_member[$rule->get_name_O()])*/) {
                    if ($rule->get_type_relation() === 'x') {
                        $sorted_vso['VO'][$key_rule] = $rule;
                    } else {
                        $sorted_vso['OV'][$key_rule] = $rule;
                    }
                } elseif ($rule->get_name_SV() !== null && !empty($this->cache_nf_member[$rule->get_name_SV()])) {
                    $sorted_vso['VS'][$key_rule] = $rule;
                }
            } elseif ($rule->get_name_O() !== null && !empty($this->cache_nf_member[$rule->get_name_O()])
                && $rule->get_name_SO() !== null && !empty($this->cache_nf_member[mb_strtolower($rule->get_name_SO(), 'utf-8')])
            ) {
                $sorted_vso['OS'][$key_rule] = $rule;
            }
        }
        return $sorted_vso;
    }

    /**
     * Создаем правило
     * @param $role_main
     * @param $role_dep
     * @return \Aot\Sviaz\Rule\Base
     */
    private function createRule($role_main, $role_dep)
    {
        $roles_map = [
            'V' => RoleRegistry::VESCH,
            'O' => RoleRegistry::OTNOSHENIE,
            'SV' => RoleRegistry::SVOISTVO,
            'SO' => RoleRegistry::SVOISTVO,
        ];
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        $roles_map[$role_main]
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        $roles_map[$role_dep]
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );
        return $builder->get();
    }

    /**
     * Создание связи в пространстве МИСОТ
     * @param $seq - последовательность
     * @param $rule
     * @param string $main_field - поле главного элемента
     * @param string $depend_field - поле зависимого элемента
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    private function createSvyaz($seq, $rule, $main_field, $depend_field)
    {
        $main = $this->getNeedleMember($rule, $main_field);
        $depended = $this->getNeedleMember($rule, $depend_field);
        if ($main === false || $depended === false) {
            return false;
        }
        $rule = $this->createRule($main_field, $depend_field);
        return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $seq);
    }

    /**
     * Получение необходимого member для генерации связи
     * @param \Objects\Rule $rule
     * @param $field
     * @return \Aot\Sviaz\SequenceMember\Word\Base
     */
    private function getNeedleMember($rule, $field)
    {
        $need_prepose = false;
        $rule_prepose = $rule->get_name_PVO();
        // определяем нужен ли предлог для мембера
        if ($field === 'V' && $rule->get_type_relation() === 'y' && $rule_prepose !== null) {
            $need_prepose = true;
        }


        $member_name = mb_strtolower(call_user_func_array([$rule, 'get_name_' . $field], []), 'utf-8');
        $z = call_user_func_array([$rule, 'get_' . $field . 'z'], []);

        // создание мембера "пропуск"
        if ($field === 'O' && $member_name === 'пропуск' && empty($this->cache_z_hash_member2[$z][$member_name])) {
            $new_member = $this->createSkipMember();
            $this->cache_nf_member['пропуск'][spl_object_hash($new_member)] = $new_member;
        }

        if ($z === null) {
            $z = call_user_func_array([$rule, 'get_Oz'], []);
        }
        $members = $this->cache_nf_member[$member_name];
        if (empty($members)) {
            throw new \RuntimeException('does not have members with name = ' . $member_name);
        }
        $needle_member = null;
        # данный мембер уже участвовал в связи
        if (!empty($this->cache_z_hash_member2[$z][$member_name])) {
            $hash_member = $this->cache_z_hash_member2[$z][$member_name];
            if (empty($members[$hash_member])) {
                return false;
            }
            $needle_member = $members[$hash_member];
        } else {
            if ($need_prepose) {
                # поиск мембера с предлогом
                foreach ($members as $hash_member => $member) {
                    /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                    if (is_a($member, \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::class, true)
                        && $member->getPredlog()->getInitialForm() === $rule_prepose
                    ) {
                        $needle_member = $member;
                        $this->cache_z_hash_member2[$z][$member_name] = $hash_member;
                        break;
                    }
                }
            } elseif (!$need_prepose) {
                # поиск мембера без предлога
                foreach ($members as $hash_member => $member) {
                    /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
                    if (!is_a($member, \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::class, true)) {
                        $needle_member = $member;
                        $this->cache_z_hash_member2[$z][$member_name] = $hash_member;
                        break;
                    }
                }
            }
            # если все еще нет мембера, то берем первый попавшийся
            if ($needle_member === null) {
                foreach ($members as $hash_member => $member) {
                    $needle_member = $member;
                    $this->cache_z_hash_member2[$z][$member_name] = $hash_member;
                    break;
                }
            }
        }
        return $needle_member;

    }

    /**
     * @param $sequence
     * @return string[]
     */
    private function getSentenceArrayBySequence($sequence)
    {
//        $sentence_array = [];
//        foreach ($sequence as $member) {
//            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
//                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
//            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
//                /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
//                $sentence_array[] = $member->getSlovo()->getText();
//                $hash_member = spl_object_hash($member);
//                $initial_form_member = $member->getSlovo()->getInitialForm();
//                // начальная форма - хэш объекта - объект
//                $this->cache_nf_member[$initial_form_member][$hash_member] = $member;
//            }
//        }
//        return $sentence_array;
        $sentence_array = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
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
     * @param $sentence_string
     * @return \Objects\Rule[]
     */
    private function getOriginalSyntaxModel($sentence_string)
    {
        $mivar = new \DMivarText(['txt' => $sentence_string]);

        $mivar->syntax_model();

        $result = $mivar->getSyntaxModel();

        return !empty($result) ? $result : [];

    }

    /**
     * Создание member "пропуск"
     * return \Aot\Sviaz\SequenceMember\Word\Base
     */
    private function createSkipMember()
    {
        $slovo = \Aot\RussianMorphology\ChastiRechi\Glagol\Base::create(
            'пропуск',
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Null::create(),
            Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::create()
        );

        return \Aot\Sviaz\SequenceMember\Word\Base::create($slovo);
    }


}