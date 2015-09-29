<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:46
 */

namespace Aot\Sviaz\Processor;


use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Sviaz\Rule\Base as RuleBase;
use Aot\Sviaz\Sequence;
use Aot\Sviaz\SequenceMember\RawMemberBuilder;

class Base
{
    /** @var  RawMemberBuilder */
    protected $raw_member_builder;

    #/** @var  CacheRules */
    protected $cache;

    /**
     * @var \Aot\Sviaz\PreProcessors\Base[]
     */
    protected $pre_processing_engines;

    /**
     * @var \Aot\Sviaz\PostProcessors\Base[]
     */
    protected $post_processing_engines;

    public function __construct()
    {
        $this->raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();

        $this->cache = \Aot\Sviaz\Processor\CacheRules::create();

        $this->pre_processing_engines = [
            \Aot\Sviaz\PreProcessors\Predlog::create(),
        ];

        $this->post_processing_engines = [
            \Aot\Sviaz\PostProcessors\Duplicate::create(),
        ];
    }

    public static function create()
    {
        return new static();
    }


    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @return \Aot\Sviaz\Sequence
     */
    protected function preProcess(\Aot\Sviaz\Sequence $sequence)
    {
        $new_sequence = $sequence;

        foreach ($this->pre_processing_engines as $engine) {
            $new_sequence = $engine->run($new_sequence);
        }

        return $new_sequence;
    }

    /**
     * @param Sequence $sequence
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected function postProcess(\Aot\Sviaz\Sequence $sequence)
    {
        $sviazi = $sequence->getSviazi();

        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class, true));
        }

        if ([] === $sviazi) {
            return [];
        }


        $new_sviazi = $sviazi;

        foreach ($this->post_processing_engines as $engine) {
            $new_sviazi = $engine->run($new_sviazi);
        }

        //$sequence->setS

        return $new_sviazi;
    }

    /**
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @param array $rules
     * @return \Aot\Sviaz\Sequence[]
     */
    public function go(\Aot\Text\NormalizedMatrix $normalized_matrix, array $rules)
    {
        assert(!empty($rules));

        foreach ($rules as $rule) {
            assert(is_a($rule, RuleBase::class, true));
        }

        $raw_sequences = $this->raw_member_builder->getRawSequences($normalized_matrix);

        $sequences = [];
        foreach ($raw_sequences as $index => $raw_sequence) {

            $sequence = $this->preProcess($raw_sequence);

            $this->applyRules(
                $sequence,
                $rules
            );

            $this->postProcess($sequence);

            $sequences[] = $sequence;

        }

        return $sequences;
    }


    /**
     * @param Sequence $sequence
     * @param RuleBase[] $rules
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected function applyRules(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        assert(!empty($rules));

        foreach ($rules as $_rule) {
            assert(is_a($_rule, RuleBase::class));
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

                    /*if (!$this->cache->get([$rule, $main_candidate, $depended_candidate])) {*/
                    if (true) {
                        $result = $this->processThird(
                            $sequence,
                            $main_candidate,
                            $depended_candidate,
                            $rule
                        );

                        if (!$result) {
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

                            /*$this->cache->put([$rule, $main_candidate, $depended_candidate]);*/
                        }
                    }
                }
            }
        }

        return $sviazi;
    }

    protected function processThird(
        \Aot\Sviaz\Sequence $sequence,
        \Aot\Sviaz\SequenceMember\Base $main_candidate,
        \Aot\Sviaz\SequenceMember\Base $depended_candidate,
        \Aot\Sviaz\Rule\Base $rule
    )
    {
        $result = true;

        $third = $rule->getAssertedThird();

        if (null === $third) {
            return $result;
        }

        $result = false;

        if (PresenceRegistry::PRESENCE_PRESENT === $third->getPresence()) {

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

                    if (true === $result) {
                        break;
                    }
                }
            }

            // \find and locate

        } else if (PresenceRegistry::PRESENCE_NOT_PRESENT === $third->getPresence()) {

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

                    if (true === $result) {
                        $result = false;
                        break;
                    }
                }
            }

            if ($result === false) {
                $result = true;
            }

        } else {

            throw new \RuntimeException("unsupported presence type " . var_export($third->getPresence(), 1));
        }

        return $result;
    }


    protected function thirdFindAndLocate(
        \Aot\Sviaz\Sequence $sequence,
        \Aot\Sviaz\SequenceMember\Base $main_candidate,
        \Aot\Sviaz\SequenceMember\Base $depended_candidate,
        \Aot\Sviaz\Rule\AssertedMember\Third $third
    )
    {
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

                if (true === $result) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
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

        if ($third_position_expected === PositionRegistry::POSITION_ANY) {

            return true;

        } else if ($third_position_expected === PositionRegistry::POSITION_BETWEEN_MAIN_AND_DEPENDED) {
            if ($main_position > $depended_position) {
                if ($main_position > $third_position_actual && $third_position_actual > $depended_position) {
                    return true;
                }
            } else if ($depended_position > $main_position) {
                if ($depended_position > $third_position_actual && $third_position_actual > $main_position) {
                    return true;
                }
            }
        } else if ($third_position_expected === PositionRegistry::POSITION_AFTER_MAIN) {
            if ($third_position_actual > $main_position) {
                return true;
            }
        } else if ($third_position_expected === PositionRegistry::POSITION_BEFORE_MAIN) {
            if ($third_position_actual < $main_position) {
                return true;
            }
        } else if ($third_position_expected === PositionRegistry::POSITION_AFTER_DEPENDED) {
            if ($third_position_actual > $depended_position) {
                return true;
            }
        } else if ($third_position_expected === PositionRegistry::POSITION_BEFORE_DEPENDED) {
            if ($third_position_actual < $depended_position) {
                return true;
            }
        };

        return false;
    }
}