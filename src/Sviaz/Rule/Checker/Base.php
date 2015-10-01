<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:30
 */

namespace Aot\Sviaz\Rule\Checker;


use Aot\Persister;

/**
 * Class Base
 * @package Aot\Sviaz\Rule\Checker
 * @property \SemanticPersistence\Entities\MisotEntities\LinkChecker $dao
 */
abstract class Base
{
    use Persister;

    /**
     * @param \Aot\Sviaz\SequenceMember\Base $main_candidate
     * @param \Aot\Sviaz\SequenceMember\Base $depended_candidate
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    public function check(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        if ($main_candidate === $depended_candidate) {
            throw new \RuntimeException("wtf?!");
        }

        return true;
    }

    public static function create()
    {
        $dao = new \SemanticPersistence\Entities\MisotEntities\LinkChecker;

        $ob = new static();

        $ob->setDao($dao);

        return $ob;
    }

    /**
     * @param $id
     * @return static
     */
    public static function createById($id)
    {
        assert(is_int($id));

        $ob = new static;

        $dao = $ob
            ->getEntityManager()
            ->find(
                \SemanticPersistence\Entities\MisotEntities\LinkChecker::class,
                $id
            );

        if (null === $dao) {
            throw new \RuntimeException("bad id = " . var_export($id, 1));
        }

        $ob->setDao($dao);

        return $ob;
    }


    /**
     * @param \SemanticPersistence\Entities\MisotEntities\LinkChecker $dao
     */
    protected function setDao($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \SemanticPersistence\Entities\MisotEntities\LinkChecker
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
        return \SemanticPersistence\Entities\MisotEntities\LinkChecker::class;
    }


}