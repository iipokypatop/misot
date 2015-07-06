<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:30
 */

namespace Aot\Sviaz\Rule\AssertedLink;


class Base
{
    const POSITION_DEPENDED_ANY = 1;
    const POSITION_DEPENDED_RIGHT_AFTER_MAIN = 2;
    const POSITION_DEPENDED_AFTER_MAIN = 3;
    const POSITION_DEPENDED_RIGHT_BEFORE_MAIN = 4;
    const POSITION_DEPENDED_BEFORE_MAIN = 5;
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $asserted_main;
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $asserted_depended;
    protected $asserted_matches = [];
    protected $position;

    /**
     * Base constructor.
     * @param \Aot\Sviaz\Rule\AssertedMember\Main $asserted_main
     * @param \Aot\Sviaz\Rule\AssertedMember\Depended $asserted_depended
     */
    public function __construct(\Aot\Sviaz\Rule\AssertedMember\Main $asserted_main, \Aot\Sviaz\Rule\AssertedMember\Depended $asserted_depended)
    {
        $this->asserted_main = $asserted_main;
        $this->asserted_depended = $asserted_depended;
    }

    public static function create(\Aot\Sviaz\Rule\AssertedMember\Main $asserted_main, \Aot\Sviaz\Rule\AssertedMember\Depended $asserted_depended)
    {
        return new static($asserted_main, $asserted_depended);
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Main
     */
    public function getAssertedMain()
    {
        return $this->asserted_main;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Depended
     */
    public function getAssertedDepended()
    {
        return $this->asserted_depended;
    }

    public function addAssertedMatches(\Aot\Sviaz\Rule\AssertedLink\AssertedMatching\Base $asserted_matching)
    {
        $this->asserted_matches[] = $asserted_matching;
    }

    public function setPosition($position)
    {
        if (!in_array($position, [
            static::POSITION_DEPENDED_ANY,
            static::POSITION_DEPENDED_RIGHT_AFTER_MAIN,
            static::POSITION_DEPENDED_AFTER_MAIN,
            static::POSITION_DEPENDED_RIGHT_BEFORE_MAIN,
            static::POSITION_DEPENDED_BEFORE_MAIN,
        ], true)
        ) {
            throw new \RuntimeException("invalid position");
        }

        $this->position = $position;
    }


    public function attempt(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        return false;
    }

}