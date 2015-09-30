<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 13.07.2015
 * Time: 15:03
 */

namespace Aot\Sviaz\Rule\AssertedMember\Builder;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;


use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;

use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Depended;
use Aot\Sviaz\Rule\AssertedMember\Main;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;
use Monolog\Registry;
use Aot\RussianSyntacsis\Predlozhenie\Chasti\Registry as ChastiPredlozhenieRegistry;


abstract class Base
{
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Base */
    protected $member;

    /** @var  int */
    protected $chast_rechi_id;

    /**
     * @param string $text
     * @return $this
     */
    public function text($text)
    {
        $this->member->assertText($text);

        return $this;
    }

    public function textGroupId($id)
    {
        $this->member->assertTextGroupId($id);

        return $this;
    }

    public function morphologyEq($id)
    {
        assert(is_int($id));

        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $class = MorphologyRegistry::getClassByChastRechiAndPriznak(
            $this->chast_rechi_id,
            $id
        );

        if (is_null($class)) {
            throw new \RuntimeException("unsupported morphology id = $id for chast rechi_id = $this->chast_rechi_id");
        }

        $this->member->assertMorphology(
            $class
        );

        return $this;
    }

    public function morphologyIs($id)
    {
        assert(is_int($id));

        if (!array_key_exists($id, MorphologyRegistry::getNullClasses())) {
            throw new \RuntimeException("no null class for morphology id = " . var_export($id, 1));
        }

        if (!array_key_exists($this->chast_rechi_id, MorphologyRegistry::getNullClasses()[$id])) {
            throw new \RuntimeException("no null class for chast rechi_id = " . var_export($this->chast_rechi_id, 1));
        }

        if (is_null(MorphologyRegistry::getNullClasses()[$id][$this->chast_rechi_id])) {
            throw new \RuntimeException("unsupported morphology id = $id for chast rechi_id = $this->chast_rechi_id");
        } else {
            $class = MorphologyRegistry::getNullClasses()[$id][$this->chast_rechi_id];
        }

        $this->member->assertMorphology(
            $class
        );

        return $this;
    }


    /**
     * @param int $id
     * @return $this
     */
    public function check($id)
    {
        assert(is_int($id));

        if (empty(MemberCheckerRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id = " . $id);
        }

        $this->member->addChecker(
            MemberCheckerRegistry::getClasses()[$id]
        );

        return $this;
    }

    /**
     * @return static
     */
    public function get()
    {
        return $this->member;
    }

    public function podlezhachee()
    {
        $this->member->setChastPredlozhenya(ChastiPredlozhenieRegistry::PODLEZHACHEE);
        return $this;
    }

    public function skazuemoe()
    {
        $this->member->setChastPredlozhenya(ChastiPredlozhenieRegistry::SKAZUEMOE);
        return $this;
    }
}