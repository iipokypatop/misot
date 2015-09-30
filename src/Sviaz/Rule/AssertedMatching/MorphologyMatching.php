<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:28
 */

namespace Aot\Sviaz\Rule\AssertedMatching;


use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

/**
 * Class MorphologyMatching
 *
 * @property \SemanticPersistence\Entities\MisotEntities\MorphologyMatching $dao
 * @package Aot\Sviaz\Rule\AssertedMatching
 */
class MorphologyMatching extends Base
{


    /** @var string */
    protected $asserted_left_class;


    /** @var  MorphologyMatchingOperator\Base */
    protected $operator;


    /** @var  string */
    protected $asserted_right_class;

    protected $message;

    /**
     * @param string $asserted_left_class
     * @param MorphologyMatchingOperator\Base $operator
     * @param string $asserted_right_class
     */
    protected function __construct($asserted_left_class, MorphologyMatchingOperator\Base $operator, $asserted_right_class)
    {
        if (!is_string($asserted_left_class)) {
            throw new \RuntimeException("incorrect argument type");
        }
        if (!is_string($asserted_right_class)) {
            throw new \RuntimeException("incorrect argument type");
        }
        if (!class_exists($asserted_right_class)) {
            throw new \RuntimeException("class $asserted_right_class not found");
        }
        if (!class_exists($asserted_left_class)) {
            throw new \RuntimeException("class $asserted_left_class not found");
        }
        if (!is_a($asserted_left_class, MorphologyBase::class, true)) {
            throw new \RuntimeException("class $asserted_left_class not instanceof " . MorphologyBase::class);
        }
        if (!is_a($asserted_right_class, MorphologyBase::class, true)) {
            throw new \RuntimeException("class $asserted_right_class not instanceof " . MorphologyBase::class);
        }

        $this->asserted_left_class = $asserted_left_class;
        $this->operator = $operator;
        $this->asserted_right_class = $asserted_right_class;
    }


    /**
     * @param string $asserted_left_class
     * @param MorphologyMatchingOperator\Base $operator
     * @param string $asserted_right_class
     * @return static
     */
    public static function create($asserted_left_class, MorphologyMatchingOperator\Base $operator, $asserted_right_class)
    {
        $ob = new static($asserted_left_class, $operator, $asserted_right_class);

        $dao = new \SemanticPersistence\Entities\MisotEntities\MorphologyMatching();

        $ob->setDao($dao);

        $ob->initDao($asserted_left_class, $operator, $asserted_right_class);

        return $ob;
    }


    public static function createByDao(\SemanticPersistence\Entities\MisotEntities\MorphologyMatching $dao)
    {
        $asserted_left_class = MorphologyRegistry::getBaseClasses()[$dao->getLeftMorphology()->getId()][$dao->getLeftChastRechi()->getId()];

        $asserted_right_class = MorphologyRegistry::getBaseClasses()[$dao->getRightMorphology()->getId()][$dao->getRightChastRechi()->getId()];

        $operator = \Aot\Sviaz\Rule\AssertedMatching\OperatorRegistry::getObjectById(
            $dao->getOperator()->getId()
        );

        $ob = new static(
            $asserted_left_class,
            $operator,
            $asserted_right_class
        );

        $ob->setDao($dao);

        return $ob;
    }


    /**
     * @param MorphologyBase|\Aot\Sviaz\SequenceMember\Base $actual_left
     * @param MorphologyBase|\Aot\Sviaz\SequenceMember\Base $actual_right
     * @return bool
     */
    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual_left, \Aot\Sviaz\SequenceMember\Base $actual_right)
    {
        if ($actual_left === $actual_right) {
            throw new \RuntimeException("wtf?!");
        }

        if (!$actual_left instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            $this->message = "левый операнд должен быть наследником класса " . \Aot\Sviaz\SequenceMember\Word\Base::class;
            return false;
        }

        if (!$actual_right instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            $this->message = "правый операнд должен быть наследником класса " . \Aot\Sviaz\SequenceMember\Word\Base::class;
            return false;
        }

        $morphology_left = $actual_left->getSlovo()->getMorphologyByClass_TEMPORARY(
            $this->asserted_left_class
        );
        if (null === $morphology_left) {
            $this->message = "морфологичесекий признак левого операнда не соответствует ожидаемому";
            return false;
        }

        $morphology_right = $actual_right->getSlovo()->getMorphologyByClass_TEMPORARY(
            $this->asserted_right_class
        );
        if (null === $morphology_right) {
            $this->message = "морфологичесекий признак правого операнда не соответствует ожидаемому";
            return false;
        }

        return $this->operator->match(
            $morphology_left,
            $morphology_right
        );
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return \SemanticPersistence\Entities\MisotEntities\MorphologyMatching::class;
    }

    /**
     * @param \SemanticPersistence\Entities\MisotEntities\MorphologyMatching $dao
     */
    protected function setDao($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \SemanticPersistence\Entities\MisotEntities\MorphologyMatching
     */
    public function getDao()
    {
        return $this->dao;
    }


    protected function initDao($asserted_left_class, MorphologyMatchingOperator\Base $operator, $asserted_right_class)
    {
        $chast_rechi_id_morphology_id =
            \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getIdAndChastRehiAndMorphologyIdByBaseClass($asserted_left_class);

        if (null === $chast_rechi_id_morphology_id) {
            throw new \RuntimeException('incorrect base class ' . var_export($asserted_left_class, 1));
        }

        list($left_chast_rechi_id, $left_morphology_id) = $chast_rechi_id_morphology_id;

        $this->dao->setLeftMorphology(
            $this->getBuildMorphologyDao($left_morphology_id)
        );

        $this->dao->setLeftChastRechi(
            $this->getBuildChastRechiDao($left_chast_rechi_id)
        );


        $chast_rechi_id_morphology_id =
            \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getIdAndChastRehiAndMorphologyIdByBaseClass($asserted_right_class);

        if (null === $chast_rechi_id_morphology_id) {
            throw new \RuntimeException('incorrect base class ' . var_export($asserted_right_class, 1));
        }

        list($right_chast_rechi_id, $right_morphology_id) = $chast_rechi_id_morphology_id;

        $this->dao->setRightMorphology(
            $this->getBuildMorphologyDao($right_morphology_id)
        );

        $this->dao->setRightChastRechi(
            $this->getBuildChastRechiDao($right_chast_rechi_id)
        );

        $this->dao->setOperator(
            $this->getBuildOperatorDao($operator)
        );
    }


    /**
     * @param $morphology_id
     * @return \SemanticPersistence\Entities\MisotEntities\Morphology
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \RuntimeException
     */
    protected function getBuildMorphologyDao($morphology_id)
    {
        $dao = $this
            ->getEntityManager()
            ->find(
                \SemanticPersistence\Entities\MisotEntities\Morphology::class,
                $morphology_id
            );

        if ($dao === null) {
            throw new \RuntimeException("morphology with id = " . var_export($morphology_id, 1) . " does not exists");
        }

        return $dao;
    }


    /**
     * @param $chast_rechi_id
     * @return \SemanticPersistence\Entities\MisotEntities\ChastiRechi
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \RuntimeException
     */
    protected function getBuildChastRechiDao($chast_rechi_id)
    {
        $dao = $this
            ->getEntityManager()
            ->find(
                \SemanticPersistence\Entities\MisotEntities\ChastiRechi::class,
                $chast_rechi_id
            );

        if ($dao === null) {
            throw new \RuntimeException("chast_rechi_dao with id = " . var_export($chast_rechi_id, 1) . " does not exists");
        }

        return $dao;
    }

    /**
     * @param MorphologyMatchingOperator\Base $operator
     * @return \SemanticPersistence\Entities\MisotEntities\Operator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \RuntimeException
     */
    public function getBuildOperatorDao(MorphologyMatchingOperator\Base $operator)
    {
        $operator_id = OperatorRegistry::getIdByObject($operator);

        $dao = $this
            ->getEntityManager()
            ->find(
                \SemanticPersistence\Entities\MisotEntities\Operator::class,
                $operator_id
            );

        if ($dao === null) {
            throw new \RuntimeException("operator with id = $operator_id does not exists");
        }

        return $dao;
    }
}