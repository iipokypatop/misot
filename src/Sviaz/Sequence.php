<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:09
 */

namespace Aot\Sviaz;


use Aot\Sviaz\Homogeneity\Homogeneity;

class Sequence extends \ArrayObject
{
    protected $id;
    /** @var \Aot\Sviaz\SubSequence[] */
    protected $sub_sequences = [];
    /** @var \Aot\Sviaz\Podchinitrelnaya\Base[] */
    protected $sviazi = [];
    /** @var \Aot\Sviaz\Homogeneity\Homogeneity[] */
    protected $homogeneities = [];
    /** @var \Aot\Sviaz\PreProcessors\HomogeneitySupposed[] */
    protected $homogeneity_supposeds = [];

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
    public function setHomogeneities($homogeneities)
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
     * @brief Добавить из массива members создать одну гомогенную группу и добавить её в набор последовательности
     *
     * @param array $members
     */
    public function createAndAddHomogeneity(array $members)
    {
        $homogeneity= \Aot\Sviaz\Homogeneity\Homogeneity::create();
        $homogeneity->setMembers($members);
        $this->homogeneities[] = $homogeneity;
    }

    /**
     * @brief Получить наборы гипотез о гомогенных группах
     *
     * @return Homogeneity[]
     */
    public function getHomogeneitySupposeds()
    {
        return $this->homogeneity_supposeds;
    }

    /**
     * @brief Задать массив гипотез о гомогенных группах членов предложений
     *
     * @param \Aot\Sviaz\Homogeneity\HomogeneitySupposed[] $homogeneity_supposeds
     */
    public function setHomogeneitySupposeds(array $homogeneity_supposeds)
    {
        foreach ($homogeneity_supposeds as $homogeneity_supposed) {
            assert(is_a($homogeneity_supposed, \Aot\Sviaz\Homogeneity\HomogeneitySupposed::class), true);
        }
        $this->homogeneity_supposeds = $homogeneity_supposeds;
    }

    /**
     * @brief Добавить одну гипотезу о гомогенной группе членов предложения
     *
     * @param \Aot\Sviaz\Homogeneity\HomogeneitySupposed $hypothesis_of_homogeneity
     */
    public function addHypothesisSupposed(\Aot\Sviaz\Homogeneity\HomogeneitySupposed $hypothesis_of_homogeneity)
    {
        $this->homogeneity_supposeds[] = $hypothesis_of_homogeneity;
    }

    /**
     * @brief Получить member по номеру позиции в последовательности
     *
     * @param $position
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    public function getMemberByPosition($position)
    {
        return $this[$position];
    }
}