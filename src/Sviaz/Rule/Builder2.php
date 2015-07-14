<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 13:45
 */

namespace Aot\Sviaz\Rule;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;

class Builder2
{


    /** @var  \Aot\Sviaz\Rule\AssertedMember\Main */
    protected $main;


    /** @var  \Aot\Sviaz\Rule\AssertedMember\Depended */
    protected $depended;


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

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    public function main(\Aot\Sviaz\Rule\AssertedMember\Main $main)
    {
        $this->main = $main;
    }

    public function depended(\Aot\Sviaz\Rule\AssertedMember\Depended $main)
    {
        $this->main = $main;
    }



    /**
     * @param int $id
     * @return $this
     */
    public function dependedAndMainFind($id)
    {
        assert(is_int($id));

        throw new \RuntimeException("not implemented yet");

        if (empty(AssertedLink\Checker\Registry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id " . var_export($id, 1));
        }
        $this->link['finders'][] = $id;

        return $this;
    }

    public function get()
    {
        /** @var $asserted_main \Aot\Sviaz\Rule\AssertedMember\Main */
        $asserted_main = $this->getAssertedMember(
            AssertedMember\Main::create(),
            $this->main
        );
        /** @var $asserted_depended \Aot\Sviaz\Rule\AssertedMember\Depended */
        $asserted_depended = $this->getAssertedMember(
            AssertedMember\Depended::create(),
            $this->depended
        );

        $rule = \Aot\Sviaz\Rule\Base::create(
            $asserted_main,
            $asserted_depended
        );

        $link = \Aot\Sviaz\Rule\AssertedLink\Base::create(
            $rule
        );

        foreach ($this->getMorphologyMatchings() as $asserted_matching) {
            $link->addAssertedMatching($asserted_matching);
        }

        foreach ($this->link['checkers'] as $id) {

            $link_checker = LinkCheckerRegistry::getObjectById($id);

            $link->addChecker($link_checker);
        }


        $rule->addLink($link);

        return $rule;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching[]
     */
    protected function getMorphologyMatchings()
    {
        $asserted_matchings = [];

        foreach ($this->link['morphology_matchings'] as $morphology_matching) {

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$this->main['chast_rechi']])) {
                throw new \RuntimeException("unsupported morphology for main chast_rechi = {$this->depended['chast_rechi']}, morphology_id = " . var_export($morphology_matching, 1));
            }

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$this->depended['chast_rechi']])) {
                throw new \RuntimeException("unsupported morphology for depended chast_rechi = {$this->depended['chast_rechi']}, morphology_id =  " . var_export($morphology_matching, 1));
            }


            $left = MorphologyRegistry::getBaseClasses()[$morphology_matching][$this->main['chast_rechi']];

            $right = MorphologyRegistry::getBaseClasses()[$morphology_matching][$this->depended['chast_rechi']];

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
    public function dependedAndMainCheck($id)
    {
        assert(is_int($id));

        if (empty(AssertedLink\Checker\Registry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id " . var_export($id, 1));
        }

        $this->link['checkers'][] = $id;

        return $this;
    }

    # Зависимое после главного
    public function dependedAfterMain()
    {
        $this->dependedAndMainCheck(LinkCheckerRegistry::DependedAfterMain);
        return $this;
    }

    # Главное после зависимого
    public function dependedBeforeMain()
    {
        $this->dependedAndMainCheck(LinkCheckerRegistry::DependedBeforeMain);
        return $this;
    }

    # Зависимое после главного, стоят подряд
    public function dependedRightAfterMain()
    {
        $this->dependedAndMainCheck(LinkCheckerRegistry::DependedRightBeforeMain);
        return $this;
    }

    # Главное после зависимого, стоят подряд
    public function dependedRightBeforeMain()
    {
        $this->dependedAndMainCheck(LinkCheckerRegistry::DependedRightAfterMain);
        return $this;
    }



    public function slovo(\Aot\Sviaz\Rule\AssertedMember\Base $member)
    {
        throw new \RuntimeException("not implemented yet");
    }

    public function estSlovo($chast_rechi_id, array $morphology)
    {

        throw new \RuntimeException("not implemented yet");
        return $this;
    }

    public function netSlova($chast_rechi_id, array $morphology)
    {
        throw new \RuntimeException("not implemented yet");
        return $this;
    }

    public function netSlovaMezhduGlavnimIZavisimim($chast_rechi_id, array $morphology)
    {
        throw new \RuntimeException("not implemented yet");
        return $this;
    }

    public function estSlovoMezhduGlavnimIZavisimim($chast_rechi_id, array $morphology)
    {
        throw new \RuntimeException("not implemented yet");
        return $this;
    }


}