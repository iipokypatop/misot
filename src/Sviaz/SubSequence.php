<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.09.2015
 * Time: 9:47
 */

namespace Aot\Sviaz;


use SebastianBergmann\GlobalState\RuntimeException;

class SubSequence // Sequence -?
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
    public static function create(\Aot\Sviaz\Sequence $sequence,$index_start,$index_end)
    {
        return new static($sequence,$index_start,$index_end);
    }


    /**
     * @param Sequence $sequence
     * @param int $main_index
     * @param int $dependent_index
     * @return static []
     */
    /*
     * СТарая версия, когда у нас только одно подлежащее и одно сказуемое
    public static function createSubSequences(\Aot\Sviaz\Sequence $sequence,$main_index,$dependent_index)
    {
        assert(is_int($main_index));
        assert(is_int($dependent_index));


        if ($main_index===$dependent_index) {
            throw new \RuntimeException("wtf: последовательность состоит из одного элемента");
        }
        if ($main_index<$dependent_index) {
            return [
                static::create($sequence,0,$main_index),
                static::create($sequence,$main_index,$dependent_index),
                static::create($sequence,$dependent_index,$sequence->count()-1),
            ];
        }
        else {
            return [
                static::create($sequence,0,$dependent_index),
                static::create($sequence,$dependent_index,$main_index),
                static::create($sequence,$main_index,$sequence->count()-1),
            ];
        }
    }
    */
    /**
     * @param Sequence $sequence
     * @param \Aot\Sviaz\SequenceMember\Base[] $members
     * @return static[]
     */
    public static function createSubSequences(\Aot\Sviaz\Sequence $sequence, $members)
    {
        $array_subsequences = [];
        $start_index = 0;
        $stop_index = null;
        $sequence_count = $sequence->count();
        //начинаем проверку со второго слова
        for ($i = 1; $i < $sequence_count; $i++) {
            if (array_key_exists($i, $members)) {
                $stop_index = $i;
                $array_subsequences[] = static::create($sequence, $start_index, $stop_index);
                $start_index = $i;
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
        $position=$this->sequence->getPosition($member);
        if ($position===null)        {
            throw new \RuntimeException("wtf: позиция не определена");
        }
        return $this->index_start <= $position && $position <= $this->index_end;
    }

    public function getInterval()
    {
        return [$this->index_start,$this->index_end];
    }
}