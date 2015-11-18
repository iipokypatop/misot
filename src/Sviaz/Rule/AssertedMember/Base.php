<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 17:11
 */

namespace Aot\Sviaz\Rule\AssertedMember;

use Aot\Persister;
use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\RussianMorphology\Slovo;
use Aot\Sviaz\Role\Registry;
use Aot\Sviaz\SequenceMember;
use Aot\Text\GroupIdRegistry;

/**
 * @property \SemanticPersistence\Entities\MisotEntities\Member $dao
 */
class Base
{
    use Persister;

    protected $chast_predlozhenya = 0;

    protected function __construct()
    {

    }

    public static function create()
    {
        $dao = new \SemanticPersistence\Entities\MisotEntities\Member();

        $ob = new static;

        $ob->setDao($dao);

        return $ob;
    }

    /**
     * @param \SemanticPersistence\Entities\MisotEntities\Member $dao
     * @return static
     */
    public static function createByDao(\SemanticPersistence\Entities\MisotEntities\Member $dao)
    {
        $ob = new static;

        $ob->setDao($dao);

        return $ob;
    }

    /**
     * @return string
     */
    public function getRoleClass()
    {
        $role_id = $this->get('mivarType');

        assert(!is_null($role_id));

        if (!isset(\Aot\Sviaz\Role\Registry::getClasses()[$role_id])) {
            throw new \RuntimeException("Unsupported role id = " . var_export($role_id, 1));
        }

        return \Aot\Sviaz\Role\Registry::getClasses()[$role_id];
    }

    public function getAssertedText()
    {
        return $this->get('text');
    }

    /**
     * @return \string[]
     */
    public function getAssertedMorphologiesClasses()
    {
        if (null === $this->getDao()->getChastRechi()->getId()) {
            throw new \RuntimeException('chast rechi is not defined');
        }
        $morphologies = $this->get('morphologies');
        $ids = [];
        foreach ($morphologies as $id) {
            $ids[] = MorphologyRegistry::getClassByChastRechiAndPriznak($this->getDao()->getChastRechi()->getId(), $id);
        }

        return $ids;
    }


    /**
     * @return \SemanticPersistence\Entities\MisotEntities\Member
     */
    public function getDao()
    {
        return $this->dao;
    }

    public function assertText($asserted_text)
    {
        assert(is_string($asserted_text));

        if ($asserted_text === '') {
            throw new \RuntimeException("asserted_text is empty string");
        }
        if ($this->getAssertedTextGroupId() !== null) {
            throw new \RuntimeException("asserted_text_group_id already defined");
        }

        // пишем в дао текст
        $this->dao->setText($asserted_text);
    }

    public function getAssertedTextGroupId()
    {
        if (null === $this->getDao()->getTextGroup()) {
            return null;
        }
        return $this->getDao()->getTextGroup()->getId();
    }

    public function assertTextGroupId($asserted_text_group_id)
    {
        assert(is_int($asserted_text_group_id));

        if ($this->getAssertedText() !== null) {
            throw new \RuntimeException("asserted_text already defined");
        }

        if (!array_key_exists($asserted_text_group_id, GroupIdRegistry::getWordVariants())) {
            throw new \RuntimeException("unsupported group registry id = " . var_export($asserted_text_group_id, 1));
        }
        /** @var \SemanticPersistence\Entities\MisotEntities\TextGroup $entity_text_group */
        $entity_text_group =
            $this
                ->getEntityManager()
                ->find(
                    \SemanticPersistence\Entities\MisotEntities\TextGroup::class,
                    $asserted_text_group_id
                );

        if (empty($entity_text_group)) {
            throw new \RuntimeException("unsupported group registry id = " . var_export($asserted_text_group_id, 1));
        }

        $this->dao->setTextGroup($entity_text_group);
    }

    /**
     * @param string $asserted_chast_rechi_class
     */
    public function assertChastRechi($asserted_chast_rechi_class)
    {
        assert(is_string($asserted_chast_rechi_class));
        assert(is_a($asserted_chast_rechi_class, Slovo::class, true));

        $id_chast_rechi = intval(ChastiRechiRegistry::getIdByClass($asserted_chast_rechi_class));

        /** @var \SemanticPersistence\Entities\MisotEntities\ChastiRechi $entity_chast_rechi */
        $entity_chast_rechi =
            $this
                ->getEntityManager()
                ->find(
                    \SemanticPersistence\Entities\MisotEntities\ChastiRechi::class,
                    $id_chast_rechi
                );

        if (empty($entity_chast_rechi)) {
            throw new \RuntimeException("unsupported chast rechi id = " . var_export($id_chast_rechi, 1));
        }

        $this->dao->setChastRechi($entity_chast_rechi);
    }

    /**
     * @return string
     */
    public function getAssertedChastRechiClass()
    {
        if (null === $this->getDao()->getChastRechi()) {
            return null;
        }
        return ChastiRechiRegistry::getClasses()[$this->getDao()->getChastRechi()->getId()];
    }

    /**
     * @param string $morphology_class
     */
    public function assertMorphology($morphology_class)
    {
        assert(is_a($morphology_class, MorphologyBase::class, true));

        if (null === $this->getAssertedChastRechiClass()) {
            throw new \RuntimeException("asserted_chast_rechi_class is not defined");
        }

        if (!MorphologyRegistry::checkMatchByChastRechiClassAndPriznakClass($this->getAssertedChastRechiClass(),
            $morphology_class)
        ) {
            throw new \RuntimeException("chastRechi and priznakClass does not match");
        }

        // пишем в dao морфологию
        $id_morphology = MorphologyRegistry::getIdMorphologyByClass($morphology_class);

        /** @var \SemanticPersistence\Entities\MisotEntities\Morphology $entity */
        $entity =
            $this
                ->getEntityManager()
                ->find(
                    \SemanticPersistence\Entities\MisotEntities\Morphology::class,
                    $id_morphology
                );

        if (empty($entity)) {
            throw new \RuntimeException("morphology class is not defined");
        }

        // если нет в дао или не соответствует ID морфологии
        if (false === $this->dao->getMorphologies()->contains($entity)) {
            $this->dao->addMorphology($entity);
        }
    }

    /**
     * @param string $checker_class
     */
    public function addChecker($checker_class)
    {
        if (!is_string($checker_class)) {
            throw new \RuntimeException("must be string " . var_export($checker_class, 1));
        }

        if (!is_a($checker_class, \Aot\Sviaz\Rule\AssertedMember\Checker\Base::class, true)) {
            throw new \RuntimeException("unsupported checker class $checker_class");
        }

        $id_checker = Checker\Registry::getIdCheckerByClass($checker_class);

        /** @var \SemanticPersistence\Entities\MisotEntities\MemberChecker $entity */
        $entity =
            $this
                ->getEntityManager()
                ->find(
                    \SemanticPersistence\Entities\MisotEntities\MemberChecker::class,
                    $id_checker
                );

        if (empty($entity)) {
            throw new \RuntimeException("morphology class is not defined");
        }

        if (
            $this->dao->getCheckers()->isEmpty() || false === $this->dao->getCheckers()->contains($entity)
        ) {
            $this->dao->addChecker($entity);
        }
    }

    /**
     * @param $role_class
     */
    public function setRoleClass($role_class)
    {
        assert(is_string($role_class));
        assert(is_a($role_class, \Aot\Sviaz\Role\Base::class, true));

        # dao
        $id_role = Registry::getIdByClass($role_class);

        $entity_role =
            $this
                ->getEntityManager()
                ->find(
                    \SemanticPersistence\Entities\SemanticEntities\MivarType::class,
                    $id_role
                );

        if (empty($entity_role)) {
            throw new \RuntimeException("unsupported role id = " . var_export($id_role, 1));
        }

        /** @var \SemanticPersistence\Entities\SemanticEntities\MivarType $entity_role */
        $this->dao->setMivarType($entity_role);
    }


    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        if ($actual instanceof \Aot\Sviaz\SequenceMember\Word\Base) {

            if (null !== $this->getAssertedText()) {
                if (strtolower($actual->getSlovo()->getText()) !== strtolower($this->getAssertedText())) {
                    return false;
                }
            }

            if (null !== $this->getAssertedTextGroupId()) {
                if (empty(GroupIdRegistry::getWordVariants()[$this->getAssertedTextGroupId()])) {
                    return false;
                }

                if (!in_array(strtolower($actual->getSlovo()->getText()),
                    GroupIdRegistry::getWordVariants()[$this->getAssertedTextGroupId()], true)
                ) {
                    return false;
                }
            }

            if (null !== $this->getAssertedChastRechiClass()) {
                if (!is_a($actual->getSlovo(), $this->getAssertedChastRechiClass(), true)) {
                    return false;
                }
            }

            foreach ($this->getAssertedMorphologiesClasses() as $asserted_morphology) {

                $morphology = $actual->getSlovo()->getMorphologyByClass_TEMPORARY($asserted_morphology);

                if (null === $morphology) {
                    return false;
                }
            }

            return true;

        } else {
            if ($actual instanceof \Aot\Sviaz\SequenceMember\Punctuation) {

                return true;
            }
        }

        throw new \RuntimeException("unsupported sequence_member type " . get_class($actual));
    }

    /**
     * @param \SemanticPersistence\Entities\MisotEntities\Member $dao
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
        return \SemanticPersistence\Entities\MisotEntities\Member::class;
    }

    /**
     * @return string[]
     */
    public function getCheckerClasses()
    {
        $checker_classes = [];
        foreach ($this->dao->getCheckers() as $checker_dao) {
            /** @var $checker_dao \SemanticPersistence\Entities\MisotEntities\MemberChecker */

            $checker_classes[] = \Aot\Sviaz\Rule\AssertedMember\Checker\Registry::getClasses()[$checker_dao->getId()];
        }

        return $checker_classes;

    }

    /**
     * @param int $chast_id
     */
    public function setChastPredlozhenya($chast_id)
    {
        assert(is_int($chast_id));

        if (empty(\Aot\RussianSyntacsis\Sentence\Member\Role\Registry::getClasses()[$chast_id])) {
            throw new \RuntimeException("unsupported chast predlozhenya id = " . var_export($chast_id, 1));
        }

        $this->chast_predlozhenya = $chast_id;
    }

    /**
     * @return int
     */
    public function getChastPredlozhenya()
    {
        return $this->chast_predlozhenya;
    }


}