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

class Builder
{
    protected $main = [];
    protected $main_default = [
        'role' => null,
        'text' => null,
        'text_group_id' => null,
        'chast_rechi' => null,
        'checkers' => [],
        'morphology' => [],
    ];
    protected $depended = [];
    protected $depended_default = [
        'role' => null,
        'text' => null,
        'text_group_id' => null,
        'chast_rechi' => null,
        'checkers' => [],
        'morphology' => [],
    ];
    protected $link;
    protected $link_default = [
        'morphology_matchings' => [],
        'checkers' => [],
        'finders' => [],
    ];


    protected function __construct()
    {
        $this->main = $this->main_default;
        $this->depended = $this->depended_default;
        $this->link = $this->link_default;
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }


    /**
     * @param string $text
     * @return $this
     */
    public function mainText($text)
    {
        assert(is_string($text));

        $this->main['text'] = $text;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainTextGroupId($id)
    {
        assert(is_int($id));

        if (!array_key_exists($id, GroupIdRegistry::getWordVariants())) {
            throw new \RuntimeException("unsupported text_group_group_id = " . $id);
        }

        $this->main['text_group_id'] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainChastRechi($id)
    {
        assert(is_int($id));

        if (empty(ChastiRechiRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . $id);
        }

        $this->main['chast_rechi'] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainCheck($id)
    {
        assert(is_int($id));

        if (empty(MemberCheckerRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id = " . $id);
        }

        $this->main['checkers'][] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainMorphology($id)
    {
        assert(is_int($id));

        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->main['morphology'][] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainRole($id)
    {
        assert(is_int($id));

        if (empty(RoleRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported role id $id");
        }

        $this->main['role'] = $id;

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function dependedText($text)
    {
        assert(is_string($text));

        $this->depended['text'] = $text;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedTextGroupId($id)
    {
        assert(is_int($id));

        if (!array_key_exists($id, GroupIdRegistry::getWordVariants())) {
            throw new \RuntimeException("unsupported text_group_group_id = " . $id);
        }

        $this->depended['text_group_id'] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedChastRechi($id)
    {
        assert(is_int($id));

        if (empty(ChastiRechiRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported chast rechi id = " . $id);
        }

        $this->depended['chast_rechi'] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedCheck($id)
    {
        assert(is_int($id));

        if (empty(MemberCheckerRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported checker id = " . $id);
        }

        $this->depended['checkers'][] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedMorphology($id)
    {
        assert(is_int($id));

        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->depended['morphology'][] = $id;

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function dependedRole($id)
    {
        assert(is_int($id));

        if (empty(RoleRegistry::getClasses()[$id])) {
            throw new \RuntimeException("unsupported role id $id");
        }

        $this->depended['role'] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedAndMainMorphologyMatching($id)
    {
        assert(is_int($id));

        if (empty(MorphologyRegistry::getBaseClasses()[$id])) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->link['morphology_matchings'][] = $id;

        return $this;
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

    /**
     * @param AssertedMember\Base $member
     * @param $config
     * @return AssertedMember\Base
     */
    protected function getAssertedMember(\Aot\Sviaz\Rule\AssertedMember\Base $member, $config)
    {
        if (null === $config['chast_rechi']) {

            throw new \RuntimeException("chast_rechi must be defined!");

        } else {
            $member->assertChastRechi(
                ChastiRechiRegistry::getClasses()[$config['chast_rechi']]
            );
        }

        if (null === $config['role']) {

            throw new \RuntimeException("role must be defined!");

        } else {
            $member->setRole(
                forward_static_call_array([RoleRegistry::getClasses()[$config['role']], 'create'], [])
            );
        }

        if (null !== $config['text']) {
            $member->assertText($config['text']);
        }

        if (null !== $config['text_group_id']) {
            $member->assertTextGroupId($config['text_group_id']);
        }

        if (null !== $config['checkers']) {
            foreach ($config['checkers'] as $checker_id) {
                $checker_class = MemberCheckerRegistry::getClasses()[$checker_id];
                $member->addChecker($checker_class);
            }
        }

        if (null !== $config['morphology']) {
            foreach ($config['morphology'] as $morphology) {
                foreach (MorphologyRegistry::getClasses() as $priznak_group => $variants) {
                    foreach ($variants as $priznak => $classes) {
                        if ($priznak !== $morphology) {
                            continue;
                        }
                        if (empty($classes[$config['chast_rechi']])) {
                            throw new \RuntimeException("где же признак $morphology ???");
                        }

                        $member->assertMorphology(
                            $classes[$config['chast_rechi']]
                        );
                    }
                }
            }
        }

        return $member;
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
     * @param int $role_id
     * @return $this
     */
    public function suschestvitelnoe($role_id)
    {
        assert(is_int($role_id));

        $this->mainRole($role_id);
        $this->mainChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE);
        return $this;
    }

    /**
     * @param int $role_id
     * @return $this
     */
    public function withSuschestvitelnoe($role_id)
    {
        assert(is_int($role_id));

        $this->dependedRole($role_id);
        $this->dependedChastRechi(ChastiRechiRegistry::SUSCHESTVITELNOE);
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


    public function estSlovo($chast_rechi_id, array $morphology)
    {
        return $this;
    }

    public function netSlova($chast_rechi_id, array $morphology)
    {
        return $this;
    }

    public function netSlovaMezhduGlavnimIZavisimim($chast_rechi_id, array $morphology)
    {
        return $this;
    }

    public function estSlovoMezhduGlavnimIZavisimim($chast_rechi_id, array $morphology)
    {
        return $this;
    }
}

