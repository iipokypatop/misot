<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:30
 */

namespace Aot\Sviaz\Rule\AssertedLink;


use Aot\Persister;
use Aot\Sviaz\Podchinitrelnaya\Registry;

/**
 * Class Base
 * @property \AotPersistence\Entities\Link $dao
 * @package Aot\Sviaz\Rule\AssertedLink
 */
class Base
{
    use Persister;

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

    /** @var string */
    protected $type_class = \Aot\Sviaz\Podchinitrelnaya\Base::class;


    /**
     * @return string
     */
    public function getTypeClass()
    {
        return $this->type_class;
    }

    /**
     * @param string $type_class
     */
    public function setTypeClass($type_class)
    {
        assert(is_string($type_class));

        if (!is_a($type_class, \Aot\Sviaz\Podchinitrelnaya\Base::class, true)) {
            throw new \RuntimeException("incorrect type_class " . var_export($type_class, 1));
        }

        #dao
        // @todo temporary!
        $id_type_class = Registry::getIdLinkByClass(\Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class);

        // если нет в дао или не соответствует ID части речи
        if (empty($this->dao->getTypeLink()) || $this->dao->getTypeLink()->getId() !== $id_type_class) {
            // пишем в дао часть речи
            /** @var \AotPersistence\Entities\TypeLink $entity_type_link */
            $entity_type_link =
                $this
                    ->getEntityManager()
                    ->find(
                        \AotPersistence\Entities\TypeLink::class,
                        $id_type_class
                    );

            if (empty($entity_type_link)) {
                throw new \RuntimeException("unsupported type link id = " . var_export($id_type_class, 1));
            }

            $this->dao->setTypeLink($entity_type_link);

        }
        #
//        print_r($this->dao);

        $this->type_class = $type_class;
    }

    /**
     * Base constructor.
     * @param \Aot\Sviaz\Rule\Base $rule
     */
    protected function __construct(\Aot\Sviaz\Rule\Base $rule)
    {
        $this->rule = $rule;

        $this->asserted_main = $rule->getAssertedMain();

        $this->asserted_depended = $rule->getAssertedDepended();

        $this->rule->addLink($this);
    }

    /**
     * @param \Aot\Sviaz\Rule\Base $rule
     * @return static
     */
    public static function create(\Aot\Sviaz\Rule\Base $rule)
    {
        $dao = new \AotPersistence\Entities\Link();
        $dao->setRule($rule->getDao());
        $ob = new static($rule);
        $ob->setDao($dao);
        return $ob;
    }

    /**
     * @param \AotPersistence\Entities\Link $dao
     * @return static
     */
    public static function createByDao(\AotPersistence\Entities\Link $dao)
    {
        $rule = \Aot\Sviaz\Rule\Base::createByDao($dao->getRule());
        $ob = new static($rule);
        $ob->setDao($dao);
        return $ob;
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
     * @param AssertedMatching\Base|AssertedMatching\MorphologyMatching $asserted_matching
     */
    public function addAssertedMatching(\Aot\Sviaz\Rule\AssertedLink\AssertedMatching\Base $asserted_matching)
    {
        $this->dao->addMatching($asserted_matching->getDao());
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
        $this->dao->addChecker($checker->getDao());
        $this->asserted_checkers[] = $checker;
    }

    /**
     * @param \AotPersistence\Entities\Link $dao
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
        return \AotPersistence\Entities\Link::class;
    }
}