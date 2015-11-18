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
    protected $pre_processing_engines = [];

    /**
     * @var \Aot\Sviaz\Processors\Base[]
     */
    protected $processing_engines = [];

    /**
     * @var \Aot\Sviaz\PostProcessors\Base[]
     */
    protected $post_processing_engines = [];

    public function __construct()
    {
        $this->raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();
    }

    public static function create()
    {
        return new static();
    }

    public static function createDefault()
    {
        $obj = static::create()
            ->attachPreProcessor(
                \Aot\Sviaz\PreProcessors\Predlog::create()
            )
            ->attachProcessor(
                \Aot\Sviaz\Processors\Misot::create()
            )
            ->attachPostProcessor(
                \Aot\Sviaz\PostProcessors\Duplicate::create()
            );

        return $obj;
    }

    /**
     * @param PreProcessors\Base $base
     * @return $this
     */
    public function attachPreProcessor(\Aot\Sviaz\PreProcessors\Base $base)
    {
        $this->pre_processing_engines[] = $base;
        return $this;
    }

    /**
     * @param Processors\Base $base
     * @return $this
     */
    public function attachProcessor(\Aot\Sviaz\Processors\Base $base)
    {
        $this->processing_engines[] = $base;
        return $this;
    }

    /**
     * @param PostProcessors\Base $base
     * @return $this
     */
    public function attachPostProcessor(\Aot\Sviaz\PostProcessors\Base $base)
    {
        $this->post_processing_engines[] = $base;
        return $this;
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
            $new_sviazi = $engine->run($sequence, $new_sviazi);
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

        //todo добавить проверку на аот и мисот
        if (false) {
            $raw_sequences = $this->raw_member_builder->getRawSequences($normalized_matrix);
        } else {
            $raw_sequences = $this->raw_member_builder->getRawOneSequence($normalized_matrix);
        }

        $sequences = [];
        foreach ($raw_sequences as $index => $raw_sequence) {

            $sequence = $this->preProcess($raw_sequence);


            $this->process(
                $sequence,
                $rules
            );


            $this->postProcess($sequence);

            $sequences[] = $sequence;

            //если АОТ
            if (true) {
                break;
            }

        }

        usort ($sequences, '\Aot\Sviaz\Processor::sortSequences');
        return $sequences;
    }

    protected function sortSequences(\Aot\Sviaz\Sequence $a, \Aot\Sviaz\Sequence $b)
    {
        $a_count_sviaz=count($a->getSviazi());
        $b_count_sviaz=count($b->getSviazi());
        if ($a_count_sviaz === $b_count_sviaz) {
            return 0;
        }
        return ($a_count_sviaz > $b_count_sviaz) ? -1 : 1;
    }
}