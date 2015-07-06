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
use Aot\Sviaz\SequenceMember\Base as SequenceMemberBase;

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
     * @param RuleBase[] $rules
     */
    public function go(\Aot\Text\NormalizedMatrix $normalized_matrix, array $rules)
    {
        assert('!empty($rules)');

        foreach ($rules as $rule) {
            assert('$rule instanceof \Aot\Sviaz\Rule\Base');
        }

        $get_raw_sequences = $this->getRawSequences($normalized_matrix);

        $this->applyRule(
            $get_raw_sequences[0],
            $rules[0]
        );

        #var_export($get_raw_sequences[0]);
    }

    protected function applyRule(\Aot\Sviaz\Sequence $sequence, RuleBase $rule)
    {
        /** @var $sequence SequenceMemberBase[] */

        $links = [];

        foreach ($sequence as $main_candidate) {
            if (!$this->tryMain($main_candidate, $rule)) {
                continue;
            }
            foreach ($sequence as $depended_candidate) {
                if ($depended_candidate === $main_candidate) {
                    continue;
                }

                if (!$this->tryDepended($depended_candidate, $rule)) {
                    continue;
                }

                $links[] = $this->tryLink($main_candidate, $depended_candidate, $rule);
            }
        }

        var_export($rule);
    }


    public function tryMain(SequenceMemberBase $sequence_member, RuleBase $rule)
    {
        $main = $rule->getMain();

        if ($sequence_member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {

            if (null !== $main->getAssertedChastRechiClass()) {
                if (get_class($sequence_member->getSlovo()) !== $main->getAssertedChastRechiClass()) {
                    return false;
                }
            }

            return true;
        }

        throw new \RuntimeException("unsupported sequence_member type");
    }


    public function tryDepended(SequenceMemberBase $sequence_member, RuleBase $rule)
    {
        $main = $rule->getMain();

        if ($sequence_member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {

            if (null !== $main->getAssertedChastRechiClass()) {
                if (get_class($sequence_member->getSlovo()) !== $main->getAssertedChastRechiClass()) {
                    return false;
                }
            }

            return true;
        }

        throw new \RuntimeException("unsupported sequence_member type");
    }

    protected function tryLink(SequenceMemberBase $main_candidate, SequenceMemberBase $depended_candidate, RuleBase $rule)
    {
        return false;
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