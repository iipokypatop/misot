<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 13:58
 */

namespace Aot\Sviaz\Rule\AssertedMember;

class Third extends Base
{

    /** @var  int */
    protected $position = PositionRegistry::POSITION_ANY;

    protected $presence = PresenceRegistry::PRESENCE_PRESENT;

    public static function createByDao(\AotPersistence\Entities\Member $dao)
    {
        $ob = parent::createByDao($dao);

        return $ob;
    }


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
        $this->presence = PresenceRegistry::PRESENCE_PRESENT;

        return $this;
    }

    public function notPresent()
    {
        $this->presence = PresenceRegistry::PRESENCE_NOT_PRESENT;

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