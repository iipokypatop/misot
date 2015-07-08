<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:46
 */

namespace Aot\Sviaz\Processor;


use Aot\Sviaz\Rule\Base as RuleBase;
use Aot\Sviaz\Sequence;

class Base
{
    /** @var  RawMemberBuilder */
    protected $raw_member_builder;

    public function __construct()
    {
        $this->raw_member_builder = \Aot\Sviaz\Processor\RawMemberBuilder::create();
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @param array $rules
     * @return \Aot\Sviaz\Base[][]
     */
    public function go(\Aot\Text\NormalizedMatrix $normalized_matrix, array $rules)
    {
        assert('!empty($rules)');

        foreach ($rules as $rule) {
            assert('$rule instanceof \Aot\Sviaz\Rule\Base');
        }

        $get_raw_sequences = $this->getRawSequences($normalized_matrix);


        $sviazi = [];

        foreach ($get_raw_sequences as $raw_sequence) {
            $sviazi[] = $this->applyRules(
                $raw_sequence,
                $rules
            );
        }

        return $sviazi;

    }


    /**
     * @param Sequence $sequence
     * @param RuleBase[] $rules
     * @return \Aot\Sviaz\Base[]
     */
    protected function applyRules(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        assert(!empty($rules));

        foreach ($rules as $_rule) {
            assert(is_a($_rule, \Aot\Sviaz\Rule\Base::class));
        }

        $sviazi = [];

        foreach ($rules as $rule) {

            foreach ($sequence as $main_candidate) {
                if (!$rule->getMain()->attempt($main_candidate)) {
                    continue;
                }

                foreach ($sequence as $depended_candidate) {
                    if ($depended_candidate === $main_candidate) {
                        continue;
                    }

                    if (!$rule->getDepended()->attempt($depended_candidate)) {
                        continue;
                    }
                    //var_export($depended_candidate);die;

                    $result = $rule->attemptLink($main_candidate, $depended_candidate, $sequence);

                    if ($result) {

                        $sviazi[] = \Aot\Sviaz\Base::create(
                            $main_candidate,
                            $depended_candidate,
                            $rule->getMain()->getRole(),
                            $rule->getDepended()->getRole()
                        );
                    }
                }
            }
        }

        return $sviazi;
    }


    protected function getRawSequences(\Aot\Text\NormalizedMatrix $normalized_matrix)
    {
        $sequences = [];

        foreach ($normalized_matrix as $array) {

            $sequences[] = $sequence = Sequence::create();

            foreach ($array as $member) {

                $raw_member = $this->raw_member_builder->build($member);

                $sequence->append($raw_member);
            }
        }

        return $sequences;
    }
}