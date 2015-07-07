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
    protected $asserted_morphology_class = [];
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Checker\Base[] */
    protected $checkers;
    /** @var  \Aot\Sviaz\Role\Base */
    protected $role;

    protected function __construct()
    {

    }

    public static function create()
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

    public function assertMorphology($morphology_class)
    {
        assert(is_a($morphology_class, MorphologyBase::class, true));

        $this->asserted_morphology_class[get_class($morphology_class)] = $morphology_class;
    }

    public function addChecker(\Aot\Sviaz\Rule\AssertedMember\Checker\Base $checker)
    {
        $this->checkers[] = $checker;
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
}