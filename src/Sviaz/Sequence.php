<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:09
 */

namespace Aot\Sviaz;

use Aot\Sviaz\Homogeneity\Homogeneity;

class Sequence extends \Judy
{

    protected $id;
    /** @var \Aot\Sviaz\SubSequence[] */
    protected $sub_sequences = [];
    /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] */
    protected $sviazi = [];
    /** @var \Aot\Sviaz\Homogeneity\Homogeneity[] */
    protected $homogeneities = [];
    /** @var \Aot\Sviaz\PreProcessors\HomogeneitySupposed[] */
    protected $array_homogeneity_supposed = [];

    /**
     * @return SubSequence[]
     */
    public function getSubSequences()
    {
        return $this->sub_sequences;
    }

    public static function create()
    {
        $ob = new static(\Judy::INT_TO_MIXED);

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
     * @param Podchinitrelnaya\Base $remove_sviaz
     * @return bool
     */
    public function removeSviaz(\Aot\Sviaz\Podchinitrelnaya\Base $remove_sviaz)
    {
        throw new \LogicException("disabled");
        foreach ($this->sviazi as $key => $sviaz) {
            if ($sviaz === $remove_sviaz) {
                unset($this->sviazi[$key]);
                return true;
            }
        }
        return false;
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

    public function append($value)
    {
        if (is_null($this->last())) {
            $index = 0;
        } else {
            $index = $this->last() + 1;
        }
        $this->offsetSet($index, $value);
    }

    /**
     * @brief Получить массив гомогенных групп членов предложения
     *
     * @return Homogeneity[]
     */
    public function getHomogeneities()
    {
        return $this->homogeneities;
    }

    /**
     * @brief Задать массив гомогенных групп членов предложения
     *
     * @param Homogeneity[] $homogeneities
     */
    public function setHomogeneities(array $homogeneities)
    {
        foreach ($homogeneities as $homogeneity) {
            assert(is_a($homogeneity, \Aot\Sviaz\Homogeneity\Homogeneity::class), true);
        }
        $this->homogeneities = $homogeneities;
    }

    /**
     * @brief Добавить одну гомогенную группу членов
     *
     * @param Homogeneity $homogeneity
     */
    public function addHomogeneity(\Aot\Sviaz\Homogeneity\Homogeneity $homogeneity)
    {
        $this->homogeneities[] = $homogeneity;
    }

    /**
     * @brief Получить наборы гипотез о гомогенных группах
     *
     * @return Homogeneity[]
     */
    public function getHomogeneitySupposed()
    {
        return $this->array_homogeneity_supposed;
    }

    /**
     * @brief Задать массив гипотез о гомогенных группах членов предложений
     *
     * @param \Aot\Sviaz\Homogeneity\HomogeneitySupposed[] $homogeneity_supposed
     */
    public function setHomogeneitySupposed(array $homogeneity_supposed)
    {
        foreach ($homogeneity_supposed as $one_homogeneity_supposed) {
            assert(is_a($one_homogeneity_supposed, \Aot\Sviaz\Homogeneity\HomogeneitySupposed::class), true);
        }
        $this->array_homogeneity_supposed = $homogeneity_supposed;
    }

    /**
     * @brief Добавить одну гипотезу о гомогенной группе членов предложения
     *
     * @param \Aot\Sviaz\Homogeneity\HomogeneitySupposed $hypothesis_of_homogeneity
     */
    public function addHypothesisSupposed(\Aot\Sviaz\Homogeneity\HomogeneitySupposed $hypothesis_of_homogeneity)
    {
        $this->array_homogeneity_supposed[] = $hypothesis_of_homogeneity;
    }

    /**
     * @brief Получить member по номеру позиции в последовательности
     *
     * @param int $position
     * @return \Aot\Sviaz\SequenceMember\Base|Null
     */
    public function getMemberByPosition($position)
    {
        assert(is_int($position));
        if (array_key_exists($position, $this)) {
            return $this[$position];
        }
        return null;
    }
}