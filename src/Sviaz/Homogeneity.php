<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 9:46
 */

namespace Aot\Sviaz;

/**
 * @brief Класс для однородных членов предложения
 *
 * Class Homogeneity
 * @package Aot\Sviaz
 */
class Homogeneity
{
    /** @var \Aot\Sviaz\SequenceMember\Base[] */
    protected $members = [];

    /**
     * Homogeneity constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return SequenceMember\Base[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param SequenceMember\Base $member
     */
    public function addMember(\Aot\Sviaz\SequenceMember\Base $member)
    {
        $this->members[] = $member;
    }

    /**
     * @param SequenceMember\Base[] $members
     */
    public function setMembers($members)
    {
        foreach ($members as $member) {
            assert(is_a($member, \Aot\Sviaz\SequenceMember\Base::class), true);
            $this->addMember($member);
        }
    }


}