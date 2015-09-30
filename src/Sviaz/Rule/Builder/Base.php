<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 12:42
 */

namespace Aot\Sviaz\Rule\Builder;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;

class Base
{
    /** @var  \Aot\Sviaz\Rule\Base */
    protected $rule;

    protected $link;
    protected $link_default = [
        'morphology_matchings' => [],
        'checkers' => [],
        'finders' => [],
        'type_class_id' => null,
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

        /*if (empty(\Aot\Sviaz\Rule\Finder\Registry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported finder id " . var_export($id, 1));
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
     */
    public function get(\Aot\Sviaz\Rule\Base $rule)
    {
        $this->rule = $rule;

        foreach ($this->getMorphologyMatchings() as $asserted_matching) {
            $rule->addAssertedMatching($asserted_matching);
        }

        foreach ($this->link['checkers'] as $id) {
            $link_checker = LinkCheckerRegistry::getObjectById($id);
            $rule->addChecker($link_checker);
        }

        if (null === $this->link['type_class_id']) {
            $rule->setTypeClass(\Aot\Sviaz\Podchinitrelnaya\Base::class);
        } else {
            $rule->setTypeClass(\Aot\Sviaz\Podchinitrelnaya\Registry::getClasses()[$this->link['type_class_id']]);
        }
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching[]
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

            $left = MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_main_class_id];

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_depended_class_id])) {
                throw new \RuntimeException("unsupported morphology for depended chast_rechi = {$asserted_depended_class_id}, morphology_id =  " . var_export($morphology_matching, 1));
            }

            $right = MorphologyRegistry::getBaseClasses()[$morphology_matching][$asserted_depended_class_id];

            $asserted_matchings[] = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
                $left,
                \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
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

        if (empty(\Aot\Sviaz\Rule\Checker\Registry::getClasses()[$id])) {
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


    /**
     * @param int $type_class_id
     * @return $this
     */
    public function type($type_class_id)
    {
        assert(is_int($type_class_id));

        if (empty(\Aot\Sviaz\Podchinitrelnaya\Registry::getClasses()[$type_class_id])) {
            throw new \RuntimeException("incorrect type_class " . var_export($type_class_id, 1));
        }

        $this->link['type_class_id'] = $type_class_id;

        return $this;
    }
}