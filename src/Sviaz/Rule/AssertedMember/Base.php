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
 * Class Base*
 * @property \AotPersistence\Entities\Member $dao
 * @package Aot\Sviaz\Rule\AssertedMember
 */
class Base
{
    use Persister;
    /** @var  string */
    protected $asserted_chast_rechi_class;

    protected $asserted_text;
    protected $asserted_text_group_id;


    /** @var string[] */
    protected $asserted_morphologies_classes = [];


    /** @var  string[] */
    protected $checker_classes;


    /** @var  \Aot\Sviaz\Role\Base */
    protected $role;

    /** @var  string */
    protected $role_class;

    protected function __construct()
    {

    }

    public static function create(/** Rule\AssertedMember\Main $main_sequence_member re */)
    {
        $dao = new \AotPersistence\Entities\Member();
        $ob = new static;
        $ob->setDao($dao);
        return $ob;
    }

    /**
     * @param \AotPersistence\Entities\Member $dao
     * @return static
     */
    public static function createByDao(\AotPersistence\Entities\Member $dao)
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
        return $this->role_class;
    }

    public function getAssertedText()
    {
        return $this->asserted_text;
    }

    /**
     * @return \string[]
     */
    public function getAssertedMorphologiesClasses()
    {
        return $this->asserted_morphologies_classes;
    }


    /**
     * @return \AotPersistence\Entities\Member
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
        if (isset($this->asserted_text_group_id))
            throw new \RuntimeException("asserted_text_group_id already defined");

        #dao
        // если нет в дао или не соответствует ID группы
        if (empty($this->getDao()->getText())) {
            // пишем в дао текст
            $this->dao->setText($asserted_text);
        }
        #
        $this->asserted_text = $asserted_text;
    }

    public function getAssertedTextGroupId()
    {
        return $this->asserted_text_group_id;
    }

    public function assertTextGroupId($asserted_text_group_id)
    {
        assert(is_int($asserted_text_group_id));

        if (isset($this->asserted_text))
            throw new \RuntimeException("asserted_text already defined");

        if (!array_key_exists($asserted_text_group_id, GroupIdRegistry::getWordVariants())) {
            throw new \RuntimeException("unsupported group registry id = " . var_export($asserted_text_group_id, 1));
        }

        #dao
        // если нет в дао или не соответствует ID группы
        if (empty($this->dao->getTextGroup()) || $this->dao->getTextGroup()->getId() !== $asserted_text_group_id) {
            // пишем в дао id группы
            /** @var \AotPersistence\Entities\TextGroup $entity_text_group */
            $entity_text_group =
                $this
                    ->getEntityManager()
                    ->find(
                        \AotPersistence\Entities\TextGroup::class,
                        $asserted_text_group_id
                    );

            if (empty($entity_text_group)) {
                throw new \RuntimeException("unsupported group registry id = " . var_export($asserted_text_group_id, 1));
            }

            $this->dao->setTextGroup($entity_text_group);
        }
        #

        $this->asserted_text_group_id = $asserted_text_group_id;
    }

    /**
     * @param string $asserted_chast_rechi_class
     */
    public function assertChastRechi($asserted_chast_rechi_class)
    {
        assert(is_string($asserted_chast_rechi_class));
        assert(is_a($asserted_chast_rechi_class, Slovo::class, true));

        #dao
        $id_chast_rechi = intval(ChastiRechiRegistry::getIdByClass($asserted_chast_rechi_class));
        // если нет в дао или не соответствует ID части речи
        if (empty($this->dao->getChastRechi()) || $this->dao->getChastRechi()->getId() !== $id_chast_rechi) {
            // пишем в дао часть речи
            /** @var \AotPersistence\Entities\ChastiRechi $entity_chast_rechi */
            $entity_chast_rechi =
                $this
                    ->getEntityManager()
                    ->find(
                        \AotPersistence\Entities\ChastiRechi::class,
                        $id_chast_rechi
                    );

            if (empty($entity_chast_rechi)) {
                throw new \RuntimeException("unsupported chast rechi id = " . var_export($id_chast_rechi, 1));
            }

            $this->dao->setChastRechi($entity_chast_rechi);

        }
        #

        $this->asserted_chast_rechi_class = $asserted_chast_rechi_class;
    }

    /**
     * @return string
     */
    public function getAssertedChastRechiClass()
    {
        return $this->asserted_chast_rechi_class;
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

        if (!MorphologyRegistry::checkMatchByChastRechiClassAndPriznakClass($this->getAssertedChastRechiClass(), $morphology_class)) {
            throw new \RuntimeException("chastRechi and priznakClass does not match");
        }

        #dao
        // пишем в dao морфологию
        $id_morphology = MorphologyRegistry::getIdMorphologyByClass($morphology_class);

        /** @var \AotPersistence\Entities\Morphology $entity */
        $entity =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\Morphology::class,
                    $id_morphology
                );

        if (empty($entity)) {
            throw new \RuntimeException("morphology class is not defined");
        }

        // если нет в дао или не соответствует ID морфологии
        if (
            $this->dao->getMorphologies()->isEmpty()
            || false === $this->dao->getMorphologies()->contains($entity)
        ) {
            $this->dao->addMorphology($entity);
        }
        #

        $this->asserted_morphologies_classes[] = $morphology_class;
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


        #dao
        // пишем в dao чекер
        $id_checker = Checker\Registry::getIdCheckerByClass($checker_class);

        /** @var \AotPersistence\Entities\MemberChecker $entity */
        $entity =
            $this
                ->getEntityManager()
                ->find(
                    \AotPersistence\Entities\MemberChecker::class,
                    $id_checker
                );

        if (empty($entity)) {
            throw new \RuntimeException("morphology class is not defined");
        }

        // если нет в дао или не соответствует ID чекера
        if (
            $this->dao->getCheckers()->isEmpty()
            || false === $this->dao->getCheckers()->contains($entity)
        ) {
            $this->dao->addChecker($entity);
        }
        #


        $this->checker_classes[] = $checker_class;
    }

    /**
     * @return \Aot\Sviaz\Role\Base
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \Aot\Sviaz\Role\Base $role
     * @deprecated
     */
    public function setRole(\Aot\Sviaz\Role\Base $role)
    {
        $this->role = $role;
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
        // если нет в дао или не соответствует ID роли
        if (empty($this->dao->getRole()) || $this->dao->getRole()->getId() !== $id_role) {
            // пишем в дао роль
            /** @var \AotPersistence\Entities\Role $entity_role */
            $entity_role =
                $this
                    ->getEntityManager()
                    ->find(
                        \AotPersistence\Entities\Role::class,
                        $id_role
                    );

            if (empty($entity_role)) {
                throw new \RuntimeException("unsupported role id = " . var_export($id_role, 1));
            }

            $this->dao->setRole($entity_role);

        }
        #

        $this->role_class = $role_class;
    }


    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        if ($actual instanceof \Aot\Sviaz\SequenceMember\Word\Base) {


            if (null !== $this->asserted_text) {
                if (strtolower($actual->getSlovo()->getText()) !== strtolower($this->asserted_text)) {
                    return false;
                }
            }

            if (null !== $this->asserted_text_group_id) {
                if (empty(GroupIdRegistry::getWordVariants()[$this->asserted_text_group_id])) {
                    return false;
                }

                if (!in_array(strtolower($actual->getSlovo()->getText()), GroupIdRegistry::getWordVariants()[$this->asserted_text_group_id], true)) {
                    return false;
                }
            }


            if (null !== $this->getAssertedChastRechiClass()) {
                if (!is_a($actual->getSlovo(), $this->getAssertedChastRechiClass(), true)) {
                    return false;
                }
            }

            foreach ($this->asserted_morphologies_classes as $asserted_morphology) {

                $morphology = $actual->getSlovo()->getMorphologyByClass_TEMPORARY($asserted_morphology);

                if (null === $morphology) {
                    return false;
                }
            }

            return true;

        } else if ($actual instanceof \Aot\Sviaz\SequenceMember\Punctuation) {

            return true;
        }

        throw new \RuntimeException("unsupported sequence_member type " . get_class($actual));
    }

    /**
     * @param \AotPersistence\Entities\Member $dao
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
        return \AotPersistence\Entities\Member::class;
    }
}