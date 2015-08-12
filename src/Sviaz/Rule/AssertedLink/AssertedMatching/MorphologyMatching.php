<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:28
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching;


use Aot\Persister;
use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

/**
 * Class MorphologyMatching
 *
 * @property \AotPersistence\Entities\MorphologyMatching $dao
 * @package Aot\Sviaz\Rule\AssertedLink\AssertedMatching
 */
class MorphologyMatching extends Base
{
    use Persister;

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
        $dao = new \AotPersistence\Entities\MorphologyMatching();
        $ob = new static($asserted_left_class, $operator, $asserted_right_class);
        $ob->setDao($dao);
        $ob->assertFieldsDao();
        return $ob;
    }

    #todo
    public static function createByDao(){
        throw new \RuntimeException("Метод еще не реализован");
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
        return \AotPersistence\Entities\MorphologyMatching::class;
    }

    /**
     * @param \AotPersistence\Entities\MorphologyMatching $dao
     */
    protected function setDao($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @return \AotPersistence\Entities\MorphologyMatching
     */
    public function getDao()
    {
        return $this->dao;
    }

    protected function assertFieldsDao()
    {
        $left_morphology_id = MorphologyRegistry::getIdMorphologyByBaseClass($this->asserted_left_class);
        $right_morphology_id = MorphologyRegistry::getIdMorphologyByBaseClass($this->asserted_left_class);
        $operator_id = OperatorRegistry::getIdByObject($this->operator);

        $entity_left_morphology =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\Morphology::class,
                    $left_morphology_id
                );

        if( $entity_left_morphology === null){
            throw new \RuntimeException("morphology with id = $left_morphology_id does not exists");
        }

        $entity_right_morphology =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\Morphology::class,
                    $right_morphology_id
                );

        if( $entity_right_morphology === null){
            throw new \RuntimeException("morphology with id = $right_morphology_id does not exists");
        }

        $entity_operator =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\Operator::class,
                    $operator_id
                );

        if( $entity_operator === null){
            throw new \RuntimeException("operator with id = $operator_id does not exists");
        }
        $this->dao->setRightMorphologyId($entity_right_morphology);
        $this->dao->setLeftMorphologyId($entity_left_morphology);
        $this->dao->setOperator($entity_operator);
    }
}