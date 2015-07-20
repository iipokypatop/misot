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

    #/** @var  CacheRules */
    protected $cache;

    public function __construct()
    {
        $this->raw_member_builder = \Aot\Sviaz\Processor\RawMemberBuilder::create();

        $this->cache = \Aot\Sviaz\Processor\CacheRules::create();
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
                if (!$rule->getAssertedMain()->attempt($main_candidate)) {
                    continue;
                }

                foreach ($sequence as $depended_candidate) {
                    if ($depended_candidate === $main_candidate) {
                        continue;
                    }

                    if (!$rule->getAssertedDepended()->attempt($depended_candidate)) {
                        continue;
                    }

                    if( !$this->cache->get([$rule, $main_candidate, $depended_candidate]) )
                    {
                        //var_export($depended_candidate);die;

                        $third = $rule->getAssertedMember();

                        $result = true;
                        if (null !== $third) {
                            if (\Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_PRESENT === $third->getPresence()) {

                                $result = false;

                                foreach ($sequence as $third_candidate) {
                                    if ($third_candidate === $main_candidate) {
                                        continue;
                                    }
                                    if ($third_candidate === $depended_candidate) {
                                        continue;
                                    }

                                    $result = $third->attempt($third_candidate);

                                    if (true === $result) {
                                        $result = $this->processPosition(
                                            $sequence->getPosition($main_candidate),
                                            $sequence->getPosition($depended_candidate),
                                            $third->getPosition(),
                                            $sequence->getPosition($third_candidate)
                                        );
                                    }
                                    if (true === $result) {
                                        break;
                                    }
                                }

                            } else if (\Aot\Sviaz\Rule\AssertedMember\Member::PRESENCE_NOT_PRESENT === $third->getPresence()) {

                                $result = true;

                                foreach ($sequence as $third_candidate) {
                                    if ($third_candidate === $main_candidate) {
                                        continue;
                                    }
                                    if ($third_candidate === $depended_candidate) {
                                        continue;
                                    }

                                    $result = $third->attempt($third_candidate);

                                    if (true === $result) {
                                        $result = $this->processPosition(
                                            $sequence->getPosition($main_candidate),
                                            $sequence->getPosition($depended_candidate),
                                            $third->getPosition(),
                                            $sequence->getPosition($third_candidate)
                                        );
                                    }
                                    if (true === $result) {
                                        $result = false;
                                        break;
                                    }
                                }
                            }
                        }

                        if (!$result) {
                            continue;
                        }

                        $result = $rule->attemptLink($main_candidate, $depended_candidate, $sequence);

                        if ($result) {

                            $sviazi[] = \Aot\Sviaz\Base::create(
                                $main_candidate,
                                $depended_candidate,
                                $rule->getAssertedMain()->getRoleClass(),
                                $rule->getAssertedDepended()->getRoleClass()
                            );
                            $this->cache->put([$rule, $main_candidate, $depended_candidate]);
                        }
                    }
                }
            }
        }

        return $sviazi;
    }


    /**
     * @param int $main_position
     * @param int $depended_position
     * @param int $third_position_expected
     * @param int $third_position_actual
     * @return bool
     */
    protected function processPosition($main_position, $depended_position, $third_position_expected, $third_position_actual)
    {
        assert(is_int($main_position));
        assert(is_int($depended_position));
        assert(is_int($third_position_expected));
        assert(is_int($third_position_actual));

        if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_ANY) {

            return true;

        } else if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BETWEEN_MAIN_AND_DEPENDED) {
            if ($main_position > $depended_position) {
                if ($main_position > $third_position_actual && $third_position_actual > $depended_position) {
                    return true;
                }
            } else if ($depended_position > $main_position) {
                if ($depended_position > $third_position_actual && $third_position_actual > $main_position) {
                    return true;
                }
            }
        } else if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_MAIN) {
            if ($third_position_actual > $main_position) {
                return true;
            }
        } else if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BEFORE_MAIN) {
            if ($third_position_actual < $main_position) {
                return true;
            }
        } else if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_AFTER_DEPENDED) {
            if ($third_position_actual > $depended_position) {
                return true;
            }
        } else if ($third_position_expected === \Aot\Sviaz\Rule\AssertedMember\Member::POSITION_BEFORE_DEPENDED) {
            if ($third_position_actual < $depended_position) {
                return true;
            }
        };

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