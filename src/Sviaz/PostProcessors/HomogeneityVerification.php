<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 11:31
 */

namespace Aot\Sviaz\PostProcessors;


class HomogeneityVerification extends Base
{
    protected function __construct()
    {
    }

    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return void
     */
    public function run(\Aot\Sviaz\Sequence $sequence, array $sviazi)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class, true));
        }

        $homogeneity_supposed = $this->getHomogeneitySupposed($sequence);

        if (empty($homogeneity_supposed)) {
            return;
        }

        $homogeneity_from_rule = $this->findHomogeneityFromSviazi($sviazi);

        if (empty($homogeneity_from_rule)) {
            return;
        }

        $this->intersect($homogeneity_supposed, $homogeneity_from_rule, $sequence);

        //$this->intersect2($homogeneity_supposed, $homogeneity_from_rule, $sequence);
    }

    /**
     * @brief Оббегает все связи и вычисляет однородные члены предложения.
     *
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return array
     */
    protected function findHomogeneityFromSviazi(array $sviazi)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class, true));
        }

        $homogeneity = [];//Все наборы гомологов

        foreach ($sviazi as $sviaz_a) {
            $set_homogeneity = [];//набор гомологов, имеющих одно главное слово
            $sviaz_a_id = $sviaz_a->getId();
            $main_member_a = $sviaz_a->getMainSequenceMember();
            $depended_member_a = $sviaz_a->getDependedSequenceMember();
            //todo Необходимо создать группы правил, в одну группу должны входить те правила, которые могут связать однородные члены. Сейчас этого нет.
            $rule_a_id = $sviaz_a->getRule()->getDao()->getId();

            $set_homogeneity[spl_object_hash($depended_member_a)] = $depended_member_a;
            //поиск ещё depended_member'ов, которые имеют главное слово === $main_member_a
            foreach ($sviazi as $sviaz_b) {
                $main_member_b = $sviaz_b->getMainSequenceMember();
                if ($main_member_b !== $main_member_a) {
                    continue;
                }
                // todo Должна быть проверка на совпадение типа правил, в случае несовпадения "continue"
                $depended_member_b = $sviaz_b->getDependedSequenceMember();
                if ($depended_member_b === $depended_member_a) {
                    continue;
                }
                $rule_b_id = $sviaz_a->getRule()->getDao()->getId();
                if ($rule_b_id === $rule_a_id) {
                    $set_homogeneity[spl_object_hash($depended_member_b)] = $depended_member_b; // $depended_member_a и $depended_member_b - ОДНОРОДНЫ!
                }
            }
            if (count($set_homogeneity) > 1) {
                $homogeneity[] = $set_homogeneity;
            }

            $set_homogeneity = [];
            $set_homogeneity[spl_object_hash($main_member_a)] = $main_member_a;
            //поиск ещё main_member'ов, которые имеют главное слово === $depended_member_a
            foreach ($sviazi as $sviaz_b) {
                $depended_member_b = $sviaz_b->getDependedSequenceMember();
                if ($depended_member_b !== $depended_member_a) {
                    continue;
                }

                // todo Должна быть проверка на совпадение типа правил, в случае несовпадения "continue"

                $main_member_b = $sviaz_b->getDependedSequenceMember();
                if ($main_member_b === $main_member_a) {
                    continue;
                }
                $rule_b_id = $sviaz_a->getRule()->getDao()->getId();
                if ($rule_b_id === $rule_a_id) {
                    $set_homogeneity[spl_object_hash($main_member_b)] = $main_member_b; // $main_member_a и $main_member_b - ОДНОРОДНЫ!
                }
            }
            if (count($set_homogeneity) > 1) {
                $homogeneity[] = $set_homogeneity;
            }
        }
        return array_values(array_unique($homogeneity, SORT_REGULAR));
    }

    /**
     * @brief Получить массив гипотез в преобразованном виде
     *
     * @param \Aot\Sviaz\Sequence $sequence
     * @return \Aot\Sviaz\SequenceMember\Base[][]
     */
    protected function getHomogeneitySupposed(\Aot\Sviaz\Sequence $sequence)
    {
        $result = [];
        $array_homogeneity_supposed = $sequence->getHomogeneitySupposed();
        foreach ($array_homogeneity_supposed as $homogeneity_supposed) {
            $result[] = $homogeneity_supposed->getMembers();
        }
        return $result;
    }

    /**
     * @brief Поиск пересечения
     *
     * @param \Aot\Sviaz\SequenceMember\Base[][] $homogeneity_supposed
     * @param \Aot\Sviaz\SequenceMember\Word\Base[][] $homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return void
     */
    protected function intersect($homogeneity_supposed, $homogeneity_from_rule, \Aot\Sviaz\Sequence $sequence)
    {
        foreach ($homogeneity_supposed as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }

        foreach ($homogeneity_from_rule as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }

        foreach ($homogeneity_supposed as $supposed) {
            if ($this->fullOverlap($supposed, $homogeneity_from_rule, $sequence)) {
                //print_r("Существует полное покрытие\n");
            } elseif ($this->partOverlap($supposed, $homogeneity_from_rule, $sequence)) {
                //print_r("Существует неоднозначное покрытие\n");
            } else {
                //print_r("Нет покрытия\n");
            }
        }

    }

    /**
     * @brief Проверка, существует ли полное покрытие гипотезы
     *
     * Если существует хотя бы один набор однородных членов по правилу, перекрывающий все элементы в гипотезе, то это полное покрытие
     *
     * @param \Aot\Sviaz\SequenceMember\Base[] $supposed
     * @param \Aot\Sviaz\SequenceMember\Word\Base[][] $array_homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    protected function fullOverlap(
        array $supposed,
        array $array_homogeneity_from_rule,
        \Aot\Sviaz\Sequence $sequence
    ) {
        foreach ($supposed as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class, true));
        }

        foreach ($array_homogeneity_from_rule as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }


        foreach ($array_homogeneity_from_rule as $homogeneity_from_rule) {
            $count_members_supposed = count($supposed);//Количество членов в гипотезе
            $count_intersect = count(array_intersect_key($supposed, $homogeneity_from_rule));

            if ($count_intersect === $count_members_supposed) {
                $homogeneity = \Aot\Sviaz\Homogeneity\Homogeneity::create($homogeneity_from_rule, $sequence);
                $sequence->addHomogeneity($homogeneity);
                return true;
            }
        }
        return false;
    }

    /**
     * @brief Проверка, существует ли частичное покрытие или покрытие несколькими правилами
     *
     * Алгоритм:
     * 1) Пробегаем по все группам однородностей, сформированным по правилам, с каждым таким правилом
     * находим пересечение нашей гипотезы
     * 2) Если пересечение больше или равно двум, следовательно есть подозрение на однородность и
     * этот случай необходимо рассмотреть     *
     * 3) После того, как собрали всех кандидатов на однородность, узнаём их кол-во, если
     * оно равно 0, следовательно ничего не подходит и мы завершаем вычисления
     * 4) Если равно 1 - сразу записываем     *
     * 5) Если кол-во более 2ух, то озвращаем все как единую группу однородностей (в будущем нужна проверка:
     * 6) todo В будущев может появиться: если есть пересечение - добавляем как одну однородность или вообще откидываем, если нет - как несколько
     *
     * @param \Aot\Sviaz\SequenceMember\Base[] $supposed
     * @param \Aot\Sviaz\SequenceMember\Word\Base[][] $array_homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    protected function partOverlap(
        array $supposed,
        array $array_homogeneity_from_rule,
        \Aot\Sviaz\Sequence $sequence
    ) {
        //todo Улучшить алгоритм наложения
        foreach ($supposed as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class, true));
        }

        foreach ($array_homogeneity_from_rule as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }
        $portions = [];
        foreach ($array_homogeneity_from_rule as $homogeneity_from_rule) {
            $intersect = array_intersect_key($supposed, $homogeneity_from_rule);
            $count_intersect = count($intersect);// Количество перекрываемых членов в гипотезе

            if ($count_intersect >= 2) {
                $portions[] = $intersect;
            }
        }
        if (count($portions) === 0) {
            return false;
        } elseif (count($portions) === 1) {
            $homogeneity = \Aot\Sviaz\Homogeneity\Homogeneity::create(current($portions), $sequence);
            $sequence->addHomogeneity($homogeneity);
            return true;
        } else {
            $tmp_array = [];
            foreach ($portions as $portion) {
                $tmp_array = array_merge($tmp_array, $portion);
            }
            $array_members = array_unique($tmp_array, SORT_REGULAR);
            $homogeneity = \Aot\Sviaz\Homogeneity\Homogeneity::create($array_members, $sequence);
            $sequence->addHomogeneity($homogeneity);
            return true;
        }

    }

    /**
     * @brief Поиск пересечения
     *
     * @param \Aot\Sviaz\SequenceMember\Base[] $homogeneity_supposed
     * @param \Aot\Sviaz\SequenceMember\Word\Base[][] $homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     */
    protected function intersect2($homogeneity_supposed, $homogeneity_from_rule, $sequence)
    {
        foreach ($homogeneity_supposed as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }

        foreach ($homogeneity_from_rule as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }

        foreach ($homogeneity_supposed as $supposed) {
            $this->Overlap($supposed, $homogeneity_from_rule, $sequence);
        }
    }

    /**
     * @param $supposed
     * @param $homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     */
    protected function Overlap($supposed, $homogeneity_from_rule, \Aot\Sviaz\Sequence $sequence)
    {
        foreach ($supposed as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class, true));
        }

        foreach ($homogeneity_from_rule as $homogeneity) {
            foreach ($homogeneity as $word) {
                assert(is_a($word, \Aot\Sviaz\SequenceMember\Word\Base::class, true));
            }
        }

        $preliminary_set = [];
        $i = 0;
        foreach ($supposed as $member) {
            $j = 0;
            foreach ($homogeneity_from_rule as $one_homogeneity_from_rule) {
                foreach ($one_homogeneity_from_rule as $member_rule) {
                    if ($member === $member_rule) {
                        $preliminary_set[$i][$j][] = $member;
                    }
                }
                $j++;
            }
            $i++;
        }

        while (true) {
            foreach ($preliminary_set as $supposed_member) {
                //если более одного элемент - пересечение, надо везде добавить дополнительные элементы
                if (count($supposed_member) > 1) {
                    $keys_rule = array_keys($supposed_member);
                    //пробегаем все ключи
                    foreach ($keys_rule as $key_rule) {

                        foreach ($preliminary_set as $supposed_member_corrected) {
                            //если в элементе есть такой ключ, то добавляем все остальные
                            if (array_key_exists($key_rule, $supposed_member_corrected)) {
                                $tmp = $key_rule;
                                foreach ($keys_rule as $key) {
                                    if ($key !== $tmp) {
                                        $supposed_member_corrected[$key] = "!!!!!";
                                    }
                                }
                            }
                        }


                    }


                }


            }
            break;
        }


    }

}