<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 17:11
 */

namespace Aot\Sviaz\Rule\AssertedMember;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\Slovo;
use Aot\Sviaz\SequenceMember;

abstract class Base
{
    /** @var  string */
    protected $asserted_chast_rechi_class;

    protected $asserted_text;
    protected $asserted_text_group_id;


    /** @var \Aot\RussianMorphology\ChastiRechi\MorphologyBase[] */
    protected $asserted_morphologies = [];

    /** @var  string[] */
    protected $checker_classes;


    /** @var  \Aot\Sviaz\Role\Base */
    protected $role;

    protected function __construct()
    {
        // Rule\AssertedMember\Main $main_sequence_member, Rule\AssertedMember\Depended $depended_sequence_member, Role\Base $main_role, Role\Base $depended_role
    }

    public static function create(/** Rule\AssertedMember\Main $main_sequence_member re */)
    {
        return new static;
    }

    public function getAssertedText()
    {
        return $this->asserted_text;
    }

    public function assertText($asserted_text)
    {
        if (isset($this->asserted_text_group_id))
            throw new \RuntimeException("asserted_text_group_id already defined");

        $this->asserted_text = $asserted_text;
    }

    public function getAssertedTextGroupId()
    {
        return $this->asserted_text_group_id;
    }

    public function assertTextGroupId($asserted_text_group_id)
    {
        if (isset($this->asserted_text))
            throw new \RuntimeException("asserted_text already defined");

        $this->asserted_text_group_id = $asserted_text_group_id;
    }

    /**
     * @param string $asserted_chast_rechi_class
     */
    public function assertChastRechi($asserted_chast_rechi_class)
    {
        assert(is_string($asserted_chast_rechi_class));
        assert(is_a($asserted_chast_rechi_class, Slovo::class, true));

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

        $this->asserted_morphologies[] = $morphology_class;
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
     */
    public function setRole(\Aot\Sviaz\Role\Base $role)
    {
        $this->role = $role;
    }

    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        if ($actual instanceof \Aot\Sviaz\SequenceMember\Word\Base) {

            if (null !== $this->getAssertedChastRechiClass()) {
                if (!is_a($actual->getSlovo(), $this->getAssertedChastRechiClass(), true)) {
                    return false;
                }
            }

            foreach ($this->asserted_morphologies as $asserted_morphology) {

                $morphology = $actual->getSlovo()->getMorphologyByClass_TEMPORARY($asserted_morphology);

                if (null === $morphology) {
                    return false;
                    //throw new \RuntimeException("признак " . is_object($asserted_morphology) ? get_class($asserted_morphology) : $asserted_morphology . " отсутствует у " . var_export($actual->getSlovo(), 1));
                }
            }

            return true;

        } else if ($actual instanceof \Aot\Sviaz\SequenceMember\Punctuation) {

            return true;
        }

        throw new \RuntimeException("unsupported sequence_member type " . get_class($actual));
    }
}