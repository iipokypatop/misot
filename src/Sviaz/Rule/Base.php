<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 12:59
 */

namespace Aot\Sviaz\Rule;


use Aot\Persister;

/**
 * Class Base
 * @property \AotPersistence\Entities\Rule $dao
 * @package Aot\Sviaz\Rule
 */
class Base
{
    use Persister;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $asserted_main;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $asserted_depended;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Third */
    protected $asserted_third;


    /** @var  \Aot\Sviaz\Rule\AssertedLink\Base[] */
    protected $links = [];

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Third
     */
    public function getAssertedThird()
    {
        return $this->asserted_third;
    }

    /**
     * @param \Aot\Sviaz\Rule\AssertedMember\Third $asserted_member
     */
    public function assertThird(\Aot\Sviaz\Rule\AssertedMember\Third $asserted_member)
    {
        # 3-ий мембер в базу не сохраняем
//        $this->dao->setThird($asserted_member->getDao());
        $this->asserted_third = $asserted_member;
    }

    /**
     * Base constructor.
     * @param AssertedMember\Main $main
     * @param AssertedMember\Depended $depended
     */
    protected function __construct(AssertedMember\Main $main, AssertedMember\Depended $depended)
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
        $dao = new \AotPersistence\Entities\Rule();
        $dao->setMain($main->getDao());
        $dao->setDepended($depended->getDao());
        $ob = new static($main, $depended);
        $ob->setDao($dao);
        return $ob;
    }

    /**
     * @param \AotPersistence\Entities\Rule $dao
     * @return static
     */
    public static function createByDao(\AotPersistence\Entities\Rule $dao)
    {

        $main = AssertedMember\Main::createByDao($dao->getMain());
        $depended = AssertedMember\Depended::createByDao($dao->getDepended());
        $ob = new static($main, $depended);
        $ob->setDao($dao);
        return $ob;
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
        return $this->asserted_third->attempt($actual);
    }

    /**
     * @param \AotPersistence\Entities\Rule $dao
     */
    protected function setDao($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\Rule::class;
    }
}

