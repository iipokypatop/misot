<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:30
 */

namespace Aot\Sviaz\Rule\AssertedLink\Checker;


use Aot\Persister;

/**
 * Class Base
 * @package Aot\Sviaz\Rule\AssertedLink\Checker
 * @property \AotPersistence\Entities\LinkChecker $dao
 */
abstract class Base
{
    use Persister;

    public function check(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        if ($main_candidate === $depended_candidate) {
            throw new \RuntimeException("wtf?!");
        }

        return true;
    }

    public static function create()
    {
        $ob = new static();
        $ob->assertDao();
        return $ob;
    }

    /**
     * @param \AotPersistence\Entities\LinkChecker $dao
     */
    protected function setDao($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \AotPersistence\Entities\LinkChecker
     */
    public function getDao()
    {
        return $this->dao;
    }


    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \AotPersistence\Entities\LinkChecker::class;
    }

    protected function assertDao()
    {
        $checker_id = Registry::getIdByClass(static::class);
        /** @var \AotPersistence\Entities\LinkChecker $dao_checker */
        $dao_checker =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\LinkChecker::class,
                    $checker_id
                );
        $this->setDao($dao_checker);
    }
}