<?php


class VSOTest extends \AotTest\AotDataStorage
{
    public function testRun()
    {
        $seq = $this->getRawSequence();
//        print_r($seq);
        $sentence_array = [];
        $cache_nf_member = [];
        /** @var \Aot\Sviaz\SequenceMember\Base $member */

        foreach ($seq as $member) {

            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
            } elseif ($member instanceof Aot\Sviaz\SequenceMember\Word\Base) {
                /** @var Aot\Sviaz\SequenceMember\Word\Base $member */
                $sentence_array[] = $member->getSlovo()->getText();
                $cache_nf_member[$member->getSlovo()->getInitialForm()] = $member;
            }

        }
//        print_r($cache_nf_member);
        $sentence_string = join(' ', $sentence_array);

//        print_r($seq);
        $mivar = new DMivarText(['txt' => $sentence_string]);
        $mivar->semantic_model();
        $vso = $mivar->getSemanticModel();

//        print_r($vso);
        $svyazi = [];
        /** @var \Objects\Rule $rule */
        foreach ($vso as $rule) {
            if ($rule->get_name_V() !== null && !empty($cache_nf_member[$rule->get_name_V()])) {

                if ($rule->get_name_O() !== null && !empty($cache_nf_member[$rule->get_name_O()])) {
                    if ($rule->get_type_relation() === 'x') {
                        $main = $cache_nf_member[$rule->get_name_V()];
                        $depended = $cache_nf_member[$rule->get_name_O()];

                        $rule_misot = \Aot\Sviaz\Rule\Base::create(
                            \Aot\Sviaz\Rule\AssertedMember\Main::create(),
                            \Aot\Sviaz\Rule\AssertedMember\Depended::create()
                        );
                        $svyazi[] = \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule_misot, $seq);

                    } else {

                        $depended = $cache_nf_member[$rule->get_name_V()];
                        $main = $cache_nf_member[$rule->get_name_O()];
                        $rule_misot = \Aot\Sviaz\Rule\Base::create(
                            \Aot\Sviaz\Rule\AssertedMember\Main::create(),
                            \Aot\Sviaz\Rule\AssertedMember\Depended::create()
                        );
                        $svyazi[] = \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule_misot, $seq);
                    }

                } elseif ($rule->get_name_SV() !== null && !empty($cache_nf_member[$rule->get_name_SV()])) {

                    $main = $cache_nf_member[$rule->get_name_V()];
                    $depended = $cache_nf_member[$rule->get_name_SV()];

                    $rule_misot = \Aot\Sviaz\Rule\Base::create(
                        \Aot\Sviaz\Rule\AssertedMember\Main::create(),
                        \Aot\Sviaz\Rule\AssertedMember\Depended::create()
                    );
                    $svyazi[] = \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule_misot, $seq);
                }
            } elseif ($rule->get_name_O() !== null && !empty($cache_nf_member[$rule->get_name_O()])
                && $rule->get_name_SO() !== null && !empty($cache_nf_member[$rule->get_name_SO()])
            ) {

                $main = $cache_nf_member[$rule->get_name_O()];
                $depended = $cache_nf_member[$rule->get_name_SO()];

                $rule_misot = \Aot\Sviaz\Rule\Base::create(
                    \Aot\Sviaz\Rule\AssertedMember\Main::create(),
                    \Aot\Sviaz\Rule\AssertedMember\Depended::create()
                );
                $svyazi[] = \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule_misot, $seq);
            }


        }
        print_r($svyazi);


    }


}