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
    /** @var  \Aot\Sviaz\Rule\Base */
    protected $rule;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $asserted_main;
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $asserted_depended;

    /** @var \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\Base[] */
    protected $asserted_matchings = [];
    /** @var \Aot\Sviaz\Rule\AssertedLink\Checker\Base[] */
    protected $asserted_checkers = [];

    /**
     * Base constructor.
     * @param \Aot\Sviaz\Rule\Base $rule
     */
    public function __construct(\Aot\Sviaz\Rule\Base $rule)
    {
        $this->rule = $rule;

        $this->asserted_main  = $rule->getAssertedMain();

        $this->asserted_depended = $rule->getAssertedDepended();

        $this->rule->addLink($this);
    }

    /**
     * @param \Aot\Sviaz\Rule\Base $rule
     * @return static
     */
    public static function create(\Aot\Sviaz\Rule\Base $rule)
    {
        return new static($rule);
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

    /**
     * @param AssertedMatching\Base $asserted_matching
     */
    public function addAssertedMatching(\Aot\Sviaz\Rule\AssertedLink\AssertedMatching\Base $asserted_matching)
    {
        $this->asserted_matchings[] = $asserted_matching;
    }

    public function attempt(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        $result = true;

        foreach ($this->asserted_matchings as $asserted_matching) {
            $result = $asserted_matching->attempt($main_candidate, $depended_candidate);
            if (!$result) {
                return false;
            }
        }

        foreach ($this->asserted_checkers as $checker) {
            $result = $checker->check($main_candidate, $depended_candidate, $sequence);
            if (!$result) {
                return false;
            }
        }

        return $result;
    }

    /**
     * @param Checker\Base $checker
     */
    public function addChecker(\Aot\Sviaz\Rule\AssertedLink\Checker\Base $checker)
    {
        $this->asserted_checkers[] = $checker;
    }
}