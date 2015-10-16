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

        $homogeneity_from_rule = $this->findHomogeneityFromRule($sviazi);

        $this->intersect($homogeneity_supposed, $homogeneity_from_rule, $sequence);
    }

    /**
     * @brief Оббегает все связи и вычисляет однородные члены предложения.
     *
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return array
     */
    protected function findHomogeneityFromRule(array $sviazi)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class, true));
        }

        $homogeneity = [];///<Все наборы гомологов

        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi */
        foreach ($sviazi as $sviaz_a) {
            $set_homogeneity = [];///<набор гомологов, имеющих одно главное слово
            $sviaz_a_id = $sviaz_a->getId();
            $main_member_a = $sviaz_a->getMainSequenceMember();
            $depended_member_a = $sviaz_a->getDependedSequenceMember();
            //todo ТИПЫ ПРАВИЛ!!! пока что только по id
            $rule_a_id = $sviaz_a->getRule()->getDao()->getId();

            $set_homogeneity[spl_object_hash($depended_member_a)] = $depended_member_a;
            //поиск ещё depended_member'ов, которые имеют главное слово === $main_member_a
            foreach ($sviazi as $sviaz_b) {
                $main_member_b = $sviaz_b->getMainSequenceMember();
                if ($main_member_b !== $main_member_a) {
                    continue;
                }
                //Поскольку сейчас нет типа правил и тип правил подменён id'ником
//                $sviaz_b_id = $sviaz_a->getId();
//                if ($sviaz_b_id === $sviaz_a_id) {
//                    continue;
//                }
                $depended_member_b = $sviaz_b->getDependedSequenceMember();
                if ($depended_member_b === $depended_member_a) {
                    continue;
                }
                $rule_b_id = $sviaz_a->getRule()->getDao()->getId();
                if ($rule_b_id === $rule_a_id) {
                    $set_homogeneity[spl_object_hash($depended_member_b)] = $depended_member_b; ///< $depended_member_a и $depended_member_b - ОДНОРОДНЫ!
                }
            }
            if (count($set_homogeneity) > 1) {
                $homogeneity[] = $set_homogeneity;
            }

            $set_homogeneity = [];
            $set_homogeneity[spl_object_hash($main_member_a)] = $main_member_a;
            //поиск ещё depended_member'ов, которые имеют главное слово === $main_member_a
            foreach ($sviazi as $sviaz_b) {
                $depended_member_b = $sviaz_b->getDependedSequenceMember();
                if ($depended_member_b !== $depended_member_a) {
                    continue;
                }
                $sviaz_b_id = $sviaz_a->getId();
                if ($sviaz_b_id === $sviaz_a_id) {
                    continue;
                }
                $main_member_b = $sviaz_b->getDependedSequenceMember();
                if ($main_member_b === $main_member_a) {
                    continue;
                }
                $rule_b_id = $sviaz_a->getRule()->getDao()->getId();
                if ($rule_b_id === $rule_a_id) {
                    $set_homogeneity[spl_object_hash($main_member_b)] = $main_member_b; ///< $main_member_a и $main_member_b - ОДНОРОДНЫ!
                }
            }
            if (count($set_homogeneity) > 1) {
                $homogeneity[] = $set_homogeneity;
            }
        }
        $result_homogeneity = [];
        foreach ($homogeneity as $one_homogeneity) {
            $flag = true;
            foreach ($result_homogeneity as $one_result_homogeneity) {
                if (count(array_intersect_key($one_homogeneity, $one_result_homogeneity)) === count($one_homogeneity)) {
                    $flag = false;
                }
            }
            if ($flag) {
                $result_homogeneity[] = $one_homogeneity;
            }
        }
        return $result_homogeneity;
    }

    /**
     * @brief Получить массив гипотез в преобразованном виде
     *
     * @param \Aot\Sviaz\Sequence $sequence
     * @return array
     */
    private function getHomogeneitySupposed($sequence)
    {
        $result = [];
        $homogeneity_supposeds = $sequence->getHomogeneitySupposed();
        foreach ($homogeneity_supposeds as $homogeneity_supposed) {
            $result[] = $homogeneity_supposed->getMembers();
        }
        return $result;
    }

    /**
     * @brief Поиск пересечения
     *
     * @param $homogeneity_supposed
     * @param $homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     */
    protected function intersect($homogeneity_supposed, $homogeneity_from_rule, $sequence)
    {
        //todo добавить проверки?

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
     * @param $supposed
     * @param $array_homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    protected function fullOverlap(
        $supposed,
        $array_homogeneity_from_rule,
        $sequence
    ) {
        foreach ($array_homogeneity_from_rule as $homogeneity_from_rule) {
            $count_members_supposed = count($supposed);///<Количество членов в гипотезе
            $count_intersect = count(array_intersect_key($supposed, $homogeneity_from_rule));

            if ($count_intersect === $count_members_supposed) {
                $sequence->createAndAddHomogeneity($homogeneity_from_rule);
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
     * @param $supposed
     * @param $array_homogeneity_from_rule
     * @param $sequence
     * @return bool
     */
    protected function partOverlap(
        $supposed,
        $array_homogeneity_from_rule,
        \Aot\Sviaz\Sequence $sequence
    ) {
        $portions = [];
        foreach ($array_homogeneity_from_rule as $homogeneity_from_rule) {
            $intersect = array_intersect_key($supposed, $homogeneity_from_rule);
            $count_intersect = count($intersect);///< Количество перекрываемых членов в гипотезе

            if ($count_intersect >= 2) {
                $portions[] = $intersect;
            }
        }
        if (count($portions) === 0) {
            return false;
        } elseif (count($portions) === 1) {
            $sequence->createAndAddHomogeneity(current($portions));
            return true;
        } else {
            $tmp_array = [];
            foreach ($portions as $portion) {
                $tmp_array = array_merge($tmp_array, $portion);
            }
            $array_members = array_unique($tmp_array, SORT_REGULAR);
            $sequence->createAndAddHomogeneity($array_members);
            return true;
        }

    }

}