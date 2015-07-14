<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 13:58
 */

namespace Aot\Sviaz\Rule\AssertedMember;


class Member extends Base
{
    const POSITION_ANY = 1;
    const POSITION_BETWEEN_MAIN_AND_DEPENDED = 2;
    const POSITION_AFTER_MAIN = 3;
    const POSITION_BEFORE_MAIN = 4;
    const POSITION_AFTER_DEPENDED = 5;
    const POSITION_BEFORE_DEPENDED = 6;

    const PRESENCE_PRESENT = 1;
    const PRESENCE_NOT_PRESENT = 2;

    /** @var  int */
    protected $position = self::POSITION_ANY;

    protected $presence = self::PRESENCE_PRESENT;


    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        assert(is_int($position));

        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPresence()
    {
        return $this->presence;
    }

    public function present()
    {
        $this->presence = static::PRESENCE_PRESENT;

        return $this;
    }

    public function notPresent()
    {
        $this->presence = static::PRESENCE_NOT_PRESENT;

        return $this;
    }

    public function attempt2(
        \Aot\Sviaz\SequenceMember\Base $actual
    )
    {
        $result = $this->attempt($actual);

        if (!$result) {
            return false;
        }
    }
}