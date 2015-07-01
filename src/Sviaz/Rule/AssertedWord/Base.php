<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 17:11
 */

namespace Sviaz\Rule\AssertedWord;


use RussianMorphology\ChastiRechi\MorphologyBase;
use RussianMorphology\Slovo;
use Sviaz\SequenceMember;
use Symfony\Component\Yaml\Exception\RuntimeException;

abstract class Base implements SequenceMember
{
    /** @var  MorphologyBase */
    protected $asserted_chast_rechi;

    protected $asserted_text;
    protected $asserted_text_group_id;

    protected $asserted_morphology = [];

    public static function create()
    {
        return new static;
    }

    public function getText()
    {
        return $this->asserted_text;
    }

    public function assertText($asserted_text)
    {
        if (isset($this->asserted_text_group_id))
            throw new \RuntimeException("asserted_text_group_id already defined");

        $this->asserted_text = $asserted_text;
    }

    public function getAssertedGroupId()
    {
        return $this->asserted_text_group_id;
    }

    public function assertGroupId($asserted_text_group_id)
    {
        if (isset($this->asserted_text))
            throw new \RuntimeException("asserted_text already defined");

        $this->asserted_text_group_id = $asserted_text_group_id;
    }

    public function getAssertedChastRechi()
    {
        return $this->asserted_chast_rechi;
    }

    public function assertChastRechi(Slovo $asserted_chast_rechi)
    {
        $this->asserted_chast_rechi = $asserted_chast_rechi;
    }

    public function assertMorphology(MorphologyBase $morphology)
    {
        if (isset($this->asserted_morphology[get_class($morphology)])) {
            throw new RuntimeException('redefinition of asserted_morphology not allowed');
        }

        $this->asserted_morphology[get_class($morphology)] = $morphology;
    }

    public function resolve($actualWord)
    {
        return false;
    }
}