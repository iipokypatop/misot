<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 12:42
 */

namespace Aot\Sviaz\Rule\AssertedLink\Builder;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;

use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;

class Base
{
    /** @var  \Aot\Sviaz\Rule\Base */
    protected $rule;

    protected $link;
    protected $link_default = [
        'morphology_matchings' => [],
        'checkers' => [],
        'finders' => [],
    ];


    protected function __construct()
    {
        $this->link = $this->link_default;
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param int $id
     * @return $this
     */
    public function find($id)
    {
        assert(is_int($id));

        throw new \RuntimeException("no finders implemented yet");

        /*if (empty(\Aot\Sviaz\Rule\AssertedLink\Finder\Registry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id " . var_export($id, 1));
        }
        $this->link['finders'][] = $id;

        return $this;*/
    }

    /**
     * @param int $id
     * @return $this
     */
    public function morphologyMatching($id)
    {
        assert(is_int($id));

        if (empty(MorphologyRegistry::getBaseClasses()[$id])) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->link['morphology_matchings'][] = $id;

        return $this;
    }

    /**
     * @param \Aot\Sviaz\Rule\Base $rule
     * @return Base
     */
    public function get(\Aot\Sviaz\Rule\Base $rule)
    {
        $this->rule = $rule;

        $link = \Aot\Sviaz\Rule\AssertedLink\Base::create($rule);

        foreach ($this->getMorphologyMatchings() as $asserted_matching) {
            $link->addAssertedMatching($asserted_matching);
        }

        foreach ($this->link['checkers'] as $id) {

            $link_checker = LinkCheckerRegistry::getObjectById($id);

            $link->addChecker($link_checker);
        }

        return $link;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching[]
     */
    protected function getMorphologyMatchings()
    {
        $asserted_matchings = [];

        $asserted_main_class_id = ChastiRechiRegistry::getIdByClass(
            $this->rule->getAssertedMain()->getAssertedChastRechiClass()
        );

        $asserted_depended_class_id = ChastiRechiRegistry::getIdByClass(
            $this->rule->getAssertedDepended()->getAssertedChastRechiClass()
        );


        foreach ($this->link['morphology_matchings'] as $morphology_matching) {

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_main_class_id])) {
                throw new \RuntimeException("unsupported morphology for main chast_rechi = {$asserted_main_class_id}, morphology_id = " . var_export($morphology_matching, 1));
            }

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_depended_class_id])) {
                throw new \RuntimeException("unsupported morphology for depended chast_rechi = {$asserted_depended_class_id}, morphology_id =  " . var_export($morphology_matching, 1));
            }

            $left = MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_main_class_id];

            $right = MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_depended_class_id];

            $asserted_matchings[] = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
                $left,
                \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
                $right
            );
        }

        return $asserted_matchings;
    }


    /**
     * @param int $id
     * @return $this
     */
    public function check($id)
    {
        assert(is_int($id));

        if (empty(\Aot\Sviaz\Rule\AssertedLink\Checker\Registry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id " . var_export($id, 1));
        }

        $this->link['checkers'][] = $id;

        return $this;
    }

    /**
     * @return $this
     *
     * Зависимое после главного
     */
    public function dependedAfterMain()
    {
        $this->check(LinkCheckerRegistry::DependedAfterMain);

        return $this;
    }


    /**
     * @return $this
     *
     * Главное после зависимого
     */
    public function dependedBeforeMain()
    {
        $this->check(LinkCheckerRegistry::DependedBeforeMain);

        return $this;
    }

    /**
     * @return $this
     *
     * Зависимое после главного, стоят подряд
     */
    public function dependedRightAfterMain()
    {
        $this->check(LinkCheckerRegistry::DependedRightAfterMain);

        return $this;
    }

    /**
     * @return $this
     *
     * Главное после зависимого, стоят подряд
     */
    public function dependedRightBeforeMain()
    {
        $this->check(LinkCheckerRegistry::DependedRightBeforeMain);

        return $this;
    }
}