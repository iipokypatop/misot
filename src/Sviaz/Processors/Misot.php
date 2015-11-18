<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 023, 23.10.2015
 * Time: 15:13
 */

namespace Aot\Sviaz\Processors;

use Aot\Sviaz\Sequence;

use \Aot\RussianSyntacsis\Sentence\Member\Role\Registry as ChastiPredlozhenieRegistry;

class Misot extends Base
{
    public static function create()
    {
        $ob = new static();

        return $ob;
    }

    public function run(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        list($rules1, $rules2) = $this->separateRules($rules);

        $this->applyRules($sequence, $rules1);
        $this->detectSubSequences($sequence);
        $this->applyRules($sequence, $rules2);
    }


    /**
     * @param Sequence $sequence
     * @param \Aot\Sviaz\Rule\Base[] $rules
     * @return array
     */
    protected function applyRules(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        foreach ($rules as $rule) {
            assert(is_a($rule, \Aot\Sviaz\Rule\Base::class));
        }
        $sviazi = [];
        /** @var \Aot\Sviaz\Rule\Base $rule */
        foreach ($rules as $rule) {

            foreach ($sequence as $main_candidate) {
                if (!$rule->getAssertedMain()->attempt($main_candidate)) {
                    continue;
                }
                if (!is_a($main_candidate, \Aot\Sviaz\SequenceMember\Word\Base::class, true)) {
                    continue;
                }
                $sub_sequences = $sequence->findSubSequencesForMember($main_candidate);

                /* @var \Aot\Sviaz\SequenceMember\Word\Base $depended_candidate */
                foreach ($sequence as $depended_candidate) {
                    if (!is_a($depended_candidate, \Aot\Sviaz\SequenceMember\Word\Base::class, true)) {
                        continue;
                    }

                    if ($depended_candidate === $main_candidate) {
                        continue;
                    }

                    if (count($sub_sequences) >= 1) {
                        $result = false;
                        foreach ($sub_sequences as $sub_sequence) {
                            if ($sub_sequence->isMemberInSequences($depended_candidate)) {
                                $result = true;
                            }
                        }
                        if (!$result) {
                            continue;
                        }
                    }

                    if (!$rule->getAssertedDepended()->attempt($depended_candidate)) {
                        continue;
                    }


                    $result = $rule->attemptLink($main_candidate, $depended_candidate, $sequence);

                    if ($result) {
                        $sviazi[] = $sviaz = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
                            $main_candidate,
                            $depended_candidate,
                            $rule,
                            $sequence
                        );

                        $sequence->addSviaz($sviaz);

                    }
                }
            }
        }

        return $sviazi;
    }


    /**
     *
     * @param Sequence $sequence
     */
    protected function detectSubSequences(\Aot\Sviaz\Sequence $sequence)
    {
        $members = [];
        //наполняем массив

        foreach ($sequence->getSviazi() as $sviaz) {
            $main = $sviaz->getMainSequenceMember();
            $main_position = $sequence->getPosition($main);
            $members[$main_position] = $main;

            $depended = $sviaz->getDependedSequenceMember();
            $depended_position = $sequence->getPosition($depended);
            $members[$depended_position] = $depended;
        }

        ksort($members);

        $sequence->setSubSequence(
            \Aot\Sviaz\SubSequence::createSubSequences(
                $sequence,
                $members
            )
        );
    }


    /**
     * @param $rules \Aot\Sviaz\Rule\Base[]
     * @return \Aot\Sviaz\Rule\Base[][]
     */
    protected function separateRules($rules)
    {
        $type1 = [];
        $type2 = [];

        foreach ($rules as $rule) {
            if (ChastiPredlozhenieRegistry::PODLEZHACHEE === $rule->getAssertedMain()->getChastPredlozhenya()
                &&
                ChastiPredlozhenieRegistry::SKAZUEMOE === $rule->getAssertedDepended()->getChastPredlozhenya()
            ) {
                $type1[] = $rule;
            } else {
                $type2[] = $rule;
            }
        }
        return [$type1, $type2];
    }
}