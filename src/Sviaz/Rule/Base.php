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
    protected $main;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $depended;

    /** @var  \Aot\Sviaz\Rule\AssertedLink\Base[] */
    protected $links = [];

    /**
     * Base constructor.
     * @param AssertedMember\Main $main
     * @param AssertedMember\Depended $depended
     */
    public function __construct(AssertedMember\Main $main, AssertedMember\Depended $depended)
    {
        $this->main = $main;
        $this->depended = $depended;
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
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @return AssertedMember\Depended
     */
    public function getDepended()
    {
        return $this->depended;
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

    public function attemptLink(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        foreach ($this->links as $link) {

            $result = $link->attempt($main_candidate, $depended_candidate, $sequence);

            if (!$result) {
                return false;

            }
        }


        return true;
    }


    public function attemptMain(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        return $this->getMain()->attempt($actual);
    }


    public function attemptDepended(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        return $this->getDepended()->attempt($actual);
    }


}

