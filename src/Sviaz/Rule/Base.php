<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 12:59
 */

namespace Aot\Sviaz\Rule;


class Base
{
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $asserted_main;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $asserted_depended;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Member */
    protected $asserted_member;

    /** @var  \Aot\Sviaz\Rule\AssertedLink\Base[] */
    protected $links = [];


    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Member
     */
    public function getAssertedMember()
    {
        return $this->asserted_member;
    }

    /**
     * @param \Aot\Sviaz\Rule\AssertedMember\Member  $asserted_member
     */
    public function assertMember(\Aot\Sviaz\Rule\AssertedMember\Member  $asserted_member)
    {
        $this->asserted_member = $asserted_member;
    }


    /**
     * Base constructor.
     * @param AssertedMember\Main $main
     * @param AssertedMember\Depended $depended
     */
    public function __construct(AssertedMember\Main $main, AssertedMember\Depended $depended)
    {
        $this->asserted_main = $main;
        $this->asserted_depended = $depended;
    }

    /**
     * @param AssertedMember\Main $main
     * @param AssertedMember\Depended $depended
     * @return static
     */
    public static function create(AssertedMember\Main $main, AssertedMember\Depended $depended)
    {
        return new static($main, $depended);
    }

    /**
     * @return AssertedMember\Main
     */
    public function getAssertedMain()
    {
        return $this->asserted_main;
    }

    /**
     * @return AssertedMember\Depended
     */
    public function getAssertedDepended()
    {
        return $this->asserted_depended;
    }

    /**
     * @return AssertedLink\Base[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param AssertedLink\Base $link
     */
    public function addLink(AssertedLink\Base $link)
    {
        $this->links[] = $link;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Base $main_candidate
     * @param \Aot\Sviaz\SequenceMember\Base $depended_candidate
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    public function attemptLink(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        $result = false;

        foreach ($this->links as $link) {

            $result = $link->attempt($main_candidate, $depended_candidate, $sequence);

            if (false === $result) {
                return $result;
            }
        }

        return $result;
    }

    public function attemptMain(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        return $this->asserted_main->attempt($actual);
    }

    public function attemptDepended(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        return $this->asserted_depended->attempt($actual);
    }

    public function attemptMember(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        return $this->asserted_member->attempt($actual);
    }
}

