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
 * @property \SemanticPersistence\Entities\MisotEntities\RuleConfig $dao
 * @package Aot\Sviaz\Rule
 */
class Base
{
    use Persister;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $asserted_main;

    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $asserted_depended;


    /** @var \Aot\Sviaz\Rule\AssertedMatching\Base[] */
    protected $asserted_matchings = [];
    /** @var \Aot\Sviaz\Rule\Checker\Base[] */
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
     * @return AssertedMatching\Base[]
     */
    public function getAssertedMatchings()
    {
        return $this->asserted_matchings;
    }

    /**
     * @return Checker\Base[]
     */
    public function getAssertedCheckers()
    {
        return $this->asserted_checkers;
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
        $dao = new \SemanticPersistence\Entities\MisotEntities\RuleConfig();

        $dao->setMain($main->getDao());

        $dao->setDepended($depended->getDao());

        $ob = new static($main, $depended);

        $ob->setDao($dao);

        return $ob;
    }

    /**
     * @param \SemanticPersistence\Entities\MisotEntities\RuleConfig $dao
     * @return static
     */
    public static function createByDao(\SemanticPersistence\Entities\MisotEntities\RuleConfig $dao)
    {
        $main = AssertedMember\Main::createByDao($dao->getMain());

        $depended = AssertedMember\Depended::createByDao($dao->getDepended());

        $ob = new static($main, $depended);

        $ob->setDao($dao);

        foreach ($dao->getMatchings() as $matching_dao) {
            $ob->asserted_matchings[] = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::createByDao($matching_dao);
        }

        foreach ($dao->getCheckers() as $checker_dao) {
            /** @var $checker_dao \SemanticPersistence\Entities\MisotEntities\LinkChecker */
            $ob->asserted_checkers[] = \Aot\Sviaz\Rule\Checker\Registry::getObjectById($checker_dao->getId());
        }

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
     * @param \Aot\Sviaz\SequenceMember\Base $main_candidate
     * @param \Aot\Sviaz\SequenceMember\Base $depended_candidate
     * @param \Aot\Sviaz\Sequence $sequence
     * @return bool
     */
    public function attemptLink(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        return $this->attempt($main_candidate, $depended_candidate, $sequence);
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
     * @param \SemanticPersistence\Entities\MisotEntities\RuleConfig $dao
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
        return \SemanticPersistence\Entities\MisotEntities\RuleConfig::class;
    }

    /** @return \SemanticPersistence\Entities\MisotEntities\RuleConfig */
    public function getDao()
    {
        return $this->dao;
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

        $id_type_class = \Aot\Sviaz\Podchinitrelnaya\Registry::getIdLinkByClass(\Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class);

        // если нет в дао или не соответствует ID части речи
        if (empty($this->dao->getTypeLink()) || $this->dao->getTypeLink()->getId() !== $id_type_class) {
            // пишем в дао часть речи
            /** @var \SemanticPersistence\Entities\MisotEntities\TypeLink $entity_type_link */
            $entity_type_link =
                $this
                    ->getEntityManager()
                    ->find(
                        \SemanticPersistence\Entities\MisotEntities\TypeLink::class,
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
     * @param \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching $asserted_matching
     */
    public function addAssertedMatching(\Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching $asserted_matching)
    {
        $asserted_matching->getDao()->setRuleConfig($this->dao);

        $this->dao->addMatching(
            $asserted_matching->getDao()
        );

        $this->asserted_matchings[] = $asserted_matching;
    }


    /**
     * @param \Aot\Sviaz\Rule\Checker\Base $checker
     */
    public function addChecker(\Aot\Sviaz\Rule\Checker\Base $checker)
    {
        if (in_array($checker, $this->asserted_checkers, true)) {
            throw new \RuntimeException("checker already exists ! " . var_export($checker, 1));
        }

        $this->dao->addChecker(
            $checker->getDao()
        );

        $this->asserted_checkers[] = $checker;
    }
}

