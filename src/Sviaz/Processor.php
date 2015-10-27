<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:46
 */

namespace Aot\Sviaz;


use Aot\Sviaz\SequenceMember\RawMemberBuilder;

class Processor
{
    /** @var  RawMemberBuilder */
    protected $raw_member_builder;

    /**
     * @var \Aot\Sviaz\PreProcessors\Base[]
     */
    protected $pre_processing_engines;

    /**
     * @var \Aot\Sviaz\Processors\Base[]
     */
    protected $processing_engines;

    /**
     * @var \Aot\Sviaz\PostProcessors\Base[]
     */
    protected $post_processing_engines;

    public function __construct()
    {
        $this->raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();


        $this->pre_processing_engines = [
            \Aot\Sviaz\PreProcessors\Predlog::create(),
        ];

        $this->processing_engines = [
            \Aot\Sviaz\Processors\Misot::create(),
        ];


        $this->post_processing_engines = [
            \Aot\Sviaz\PostProcessors\Duplicate::create(),
        ];
    }

    public static function create()
    {
        return new static();
    }

    public static function createDefault()
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
     * @param \Aot\Sviaz\Rule\Base[] $rules
     */
    protected function process(\Aot\Sviaz\Sequence $sequence, array $rules)
    {
        assert(!empty($rules));

        foreach ($rules as $_rule) {
            assert(is_a($_rule, \Aot\Sviaz\Rule\Base::class));
        }

        foreach ($this->processing_engines as $processing_engine) {
            $processing_engine->run($sequence, $rules);
        }
    }


    /**
     * @param Sequence $sequence
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected function postProcess(\Aot\Sviaz\Sequence $sequence)
    {
        /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi */
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
            assert(is_a($rule, \Aot\Sviaz\Rule\Base::class, true));
        }

        $raw_sequences = $this->raw_member_builder->getRawSequences($normalized_matrix);

        $sequences = [];
        foreach ($raw_sequences as $index => $raw_sequence) {

            $sequence = $this->preProcess($raw_sequence);

            $this->process(
                $sequence,
                $rules
            );

            $this->postProcess($sequence);

            $sequences[] = $sequence;

        }

        return $sequences;
    }
}