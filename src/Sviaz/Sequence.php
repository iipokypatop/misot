<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:09
 */

namespace Aot\Sviaz;


class Sequence extends \ArrayObject
{

    protected $id;
    /**
     * @var \Aot\Sviaz\SubSequence[]
     */
    protected $sub_sequences = [];

    /**
     * @return SubSequence[]
     */
    public function getSubSequences()
    {
        return $this->sub_sequences;
    }

    public static function create()
    {
        $ob = new static();

        $ob->id = spl_object_hash($ob);

        return $ob;
    }

    public function getPosition(\Aot\Sviaz\SequenceMember\Base $search)
    {
        foreach ($this as $index => $member) {
            if ($search === $member) {
                return $index;
            }
        }

        return null;
    }

    /**
     * @var \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected $sviazi = [];

    public function addSviaz(\Aot\Sviaz\Podchinitrelnaya\Base $sviaz)
    {
        $this->sviazi[] = $sviaz;
    }

    /**
     * @return Podchinitrelnaya\Base[]
     */
    public function getSviazi()
    {
        return $this->sviazi;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @brief Определение к какой подпоследовательности относится данный элемент
     * @param \Aot\Sviaz\SequenceMember\Base $member
     * @return SubSequence[]
     *
     * [1] [-] [3] [=] [5] [6] [7]
     * Например элемент [1] относится только к одной группе, а подлежащее может относиться к
     * двум подпоследовательностям, поэтому возвращается массив подпоследовательностей
     */
    public function findSubSequencesForMember(\Aot\Sviaz\SequenceMember\Base $member)
    {
        $result = [];
        foreach ($this->sub_sequences as $sub_sequence) {
            if ($sub_sequence->isMemberInSequences($member)) {
                $result[] = $sub_sequence;
            }
        }
        return $result;
    }


    /**
     * @param \Aot\Sviaz\SubSequence[] $sub_sequences
     */
    public function setSubSequence(array $sub_sequences)
    {
        foreach ($sub_sequences as $sub_sequence) {
            assert(is_a($sub_sequence, \Aot\Sviaz\SubSequence::class, true));
        }
        $this->sub_sequences = $sub_sequences;
    }
}