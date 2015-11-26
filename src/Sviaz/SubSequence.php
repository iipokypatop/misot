<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.09.2015
 * Time: 9:47
 */

namespace Aot\Sviaz;


class SubSequence
{
    /**
     * @var \Aot\Sviaz\Sequence
     */
    protected $sequence;
    /**
     * @var int
     */
    protected $index_start;
    /**
     * @var int
     */
    protected $index_end;

    /**
     * SubSequence constructor.
     * @param Sequence $sequence
     * @param int $index_start
     * @param int $index_end
     */
    protected function __construct(Sequence $sequence, $index_start, $index_end)
    {
        assert(is_int($index_start));
        assert(is_int($index_end));
        $this->sequence = $sequence;
        $this->index_start = $index_start;
        $this->index_end = $index_end;
    }

    /**
     * @param Sequence $sequence
     * @param int $index_start
     * @param int $index_end
     * @return static
     */
    public static function create(\Aot\Sviaz\Sequence $sequence, $index_start, $index_end)
    {
        return new static($sequence, $index_start, $index_end);
    }


    /**
     * @param Sequence $sequence
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     * @return static[]
     */
    public static function createSubSequences(\Aot\Sviaz\Sequence $sequence, array $members)
    {
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class, true));
        }

        $array_subsequences = [];
        $start_index = 0;
        $stop_index = null;
        $sequence_count = $sequence->count();
        foreach ($sequence as $index => $sequence_member) {
            foreach ($members as $member) {
                if ($sequence_member === $member) {
                    if ($index === 0) {
                        continue 2;
                    }
                    $stop_index = $index;
                    if ($start_index !== $stop_index) {
                        $array_subsequences[] = static::create($sequence, $start_index, $stop_index);
                    }
                    $start_index = $index;
                }
            }
        }

        if ($start_index !== $sequence_count - 1) {
            $array_subsequences[] = static::create($sequence, $start_index, $sequence_count - 1);
        }

        return $array_subsequences;
    }


    /**
     * @param \Aot\Sviaz\SequenceMember\Base $member
     * @return bool
     */
    public function isMemberInSequences($member)
    {
        $position = $this->sequence->getPosition($member);
        return $this->index_start <= $position && $position <= $this->index_end;
    }

    /**
     * @return int
     */
    public function getIndexStart()
    {
        return $this->index_start;
    }

    /**
     * @return int
     */
    public function getIndexEnd()
    {
        return $this->index_end;
    }
}