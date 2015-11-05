<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 11:10
 */

namespace Aot\Sviaz\PreProcessors;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;


class HomogeneitySupposed extends \Aot\Sviaz\PreProcessors\Base
{
    /**
     * @brief На данный момент - заглушечка, а должен быть реальный метод, создающий гипотезы о гомогенных группах
     *
     * @param \Aot\Sviaz\Sequence $raw_sequence
     * @return \Aot\Sviaz\Sequence
     */
    public function run(\Aot\Sviaz\Sequence $raw_sequence)
    {
        $hash_gomogeneity = [];
        foreach ($raw_sequence as $base_member) {
            if ($base_member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                continue;
            }
            if (array_key_exists(spl_object_hash($base_member), $hash_gomogeneity)) {
                continue;
            }
            $group = [];
            $group[] = $base_member;
            $hash_gomogeneity[spl_object_hash($base_member)] = $base_member;
            foreach ($raw_sequence as $other_member) {
                if ($other_member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                    continue;
                }
                if ($base_member === $other_member) {
                    continue;
                }
                if (array_key_exists(spl_object_hash($other_member), $hash_gomogeneity)) {
                    continue;
                }
                if (!$this->check($base_member, $other_member)) {
                    continue;
                }

                $hash_gomogeneity[spl_object_hash($other_member)] = $other_member;
                $group[] = $other_member;
            }
            if (count($group) > 1) {
                $raw_sequence->addHypothesisSupposed(
                    \Aot\Sviaz\Homogeneity\HomogeneitySupposed::create(
                        $group,
                        $raw_sequence
                    )
                );
            }
        }

        return $raw_sequence;
    }


    /**
     * @param \Aot\Sviaz\SequenceMember\Word\Base $first_member
     * @param \Aot\Sviaz\SequenceMember\Word\Base $second_member
     * @return bool
     */
    protected function check(
        \Aot\Sviaz\SequenceMember\Word\Base $first_member,
        \Aot\Sviaz\SequenceMember\Word\Base $second_member
    ) {
        foreach (static::getAllRules() as $rule) {
            if (
                $rule->getAssertedMain()->attempt($first_member) && $rule->getAssertedDepended()->attempt($second_member) ||
                $rule->getAssertedMain()->attempt($second_member) && $rule->getAssertedDepended()->attempt($first_member)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    protected static function getAllRules()
    {
        //todo Надо переделать
        $rules = [];
        $rules = array_merge($rules, static::getRule1());
        /*$rules = array_merge($rules, static::getRule2());
        $rules = array_merge($rules, static::getRule3());
        $rules = array_merge($rules, static::getRule4());
        $rules = array_merge($rules, static::getRule5());
        $rules = array_merge($rules, static::getRule6());
        $rules = array_merge($rules, static::getRule7());
        $rules = array_merge($rules, static::getRule8());
        $rules = array_merge($rules, static::getRule9());
        $rules = array_merge($rules, static::getRule10());
        $rules = array_merge($rules, static::getRule11());*/

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule1()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule2()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );

        $rules[] = $builder->get();

        return $rules;
    }


    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule3()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::ROD)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule4()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::ROD)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule5()
    {
        return [];
        throw new \RuntimeException("Плохое это правило, надо его перепроверить!");
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::PRICHASTIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                        ->morphologyMatching(MorphologyRegistry::ROD)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule6()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::ROD)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule7()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule8()
    {
        return [];
        throw new \RuntimeException("Плохое это правило, надо его перепроверить!");
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::PRILAGATELNOE,
                        RoleRegistry::SVOISTVO
                    )
                //->morphologyEq(MorphologyRegistry::PADESZH_VINITELNIJ)
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::ROD)
                        ->morphologyMatching(MorphologyRegistry::CHISLO)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule9()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::DEEPRICHASTIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::DEEPRICHASTIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule10()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );

        $rules[] = $builder->get();

        return $rules;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRule11()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::CHISLITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::CHISLITELNOE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(MorphologyRegistry::PADESZH)
                );

        $rules[] = $builder->get();

        return $rules;
    }

}