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

    public function run(\Aot\Sviaz\Sequence $sequence, array $sviazi)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class, true));
        }


        $homogeneity_supposed = $this->getHomogeneitySupposeds($sequence);

        $homogeneity_from_rule = $this->findHomogeneityFromRule($sviazi);

        $this->intersect($homogeneity_supposed, $homogeneity_from_rule, $sequence);
        $i = 0;
    }


    protected function findHomogeneityFromRule($sviazi)
    {
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
        return $homogeneity;
    }

    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @return array
     */
    private function getHomogeneitySupposeds($sequence)
    {
        $result = [];
        $homogeneity_supposeds = $sequence->getHomogeneitySupposeds();
        foreach ($homogeneity_supposeds as $homogeneity_supposed) {
            $result[] = $homogeneity_supposed->getMembers();
        }
        return $result;
    }

    /**
     * @param $homogeneity_supposed
     * @param $homogeneity_from_rule
     * @param \Aot\Sviaz\Sequence $sequence
     */
    protected function intersect($homogeneity_supposed, $homogeneity_from_rule, $sequence)
    {
        foreach ($homogeneity_supposed as $supposed) {
            if ($this->fullOverlap($supposed, $homogeneity_from_rule, $sequence)) {
                print_r("Существует полное покрытие\n");
            } elseif ($this->halfOverlap($supposed, $homogeneity_from_rule, $sequence)) {
                print_r("Существует неоднозначное покрытие\n");
            } else {
                print_r("Нет покрытия\n");
            }
        }

    }

    /**
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
            /* Если существует хотя бы один набор однородных членов по правилу, перекрывающий
            все элементы в гипотезе, то это полное покрытие */
            $count_members_supposed = count($supposed);///<Количество членов в гипотезе
            $count_intersect = count(array_intersect_key($supposed, $homogeneity_from_rule));

            if ($count_intersect === $count_members_supposed) {
                $sequence->createAndAddHomogeneity($homogeneity_from_rule);
                return true;
            }
        }
        return false;
    }

    protected function halfOverlap(
        $supposed,
        $array_homogeneity_from_rule,
        $sequence
    ) {
        $portions = [];
        foreach ($array_homogeneity_from_rule as $homogeneity_from_rule) {
            $count_members_supposed = count($supposed);///<Количество членов в гипотезе
            $intersect = array_intersect_key($supposed, $homogeneity_from_rule);
            $count_intersect = count($intersect);///< Количество перекрываемых членов в гипотезе

            if ($count_intersect >= 2) {
                $portions[] = $intersect;
            }
        }
        if (count($portions) === 0) {
            return false;
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