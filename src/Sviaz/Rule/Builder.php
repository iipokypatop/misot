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
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;

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
        $this->main['text'] = $text;

        return $this;
    }

    /**
     * @param int $text_group_id
     * @return $this
     */
    public function mainGroupId($text_group_id)
    {
        $this->main['text_group_id'] = $text_group_id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function mainChastRechi($id)
    {
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
        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->main['morphology'][] = $id;

        return $this;
    }

    /**
     * @param int $main_role_id
     * @return $this
     */
    public function mainRole($main_role_id)
    {
        if (empty(RoleRegistry::getClasses()[$main_role_id])) {
            throw new \RuntimeException("unsupported role id $main_role_id");
        }

        $this->main['role'] = $main_role_id;

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function dependedText($text)
    {
        $this->depended['text'] = $text;

        return $this;
    }

    /**
     * @param int $text_group_id
     * @return $this
     */
    public function dependedGroupId($text_group_id)
    {
        $this->depended['text_group_id'] = $text_group_id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedChastRechi($id)
    {
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
        if (!in_array($id, MorphologyRegistry::getLvl2(), true)) {
            throw new \RuntimeException("unsupported morphology id = " . var_export($id, 1));
        }

        $this->depended['morphology'][] = $id;

        return $this;
    }

    /**
     * @param $depended_role_id
     * @return $this
     */
    public function dependedRole($depended_role_id)
    {
        if (empty(RoleRegistry::getClasses()[$depended_role_id])) {
            throw new \RuntimeException("unsupported role id $depended_role_id");
        }

        $this->depended['role'] = $depended_role_id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedAndMainMorphologyMatching($id)
    {
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
    public function linkChecker($id)
    {
        $this->link['checkers'][] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function linkFinders($id)
    {
        $this->link['finders'][] = $id;

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function dependedAndMainCheck($id)
    {
        if (empty(\Aot\Sviaz\Rule\AssertedLink\Checker\Registry::getClasses()[$id])) {
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
        throw new \RuntimeException("not implemented yet");

        if (empty(\Aot\Sviaz\Rule\AssertedLink\Checker\Registry::getClasses()[$id])) {
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

            throw new \RuntimeException("chast_rechi must be definded!");

        } else {
            $member->assertChastRechi(
                ChastiRechiRegistry::getClasses()[$config['chast_rechi']]
            );
        }

        if (null !== $config['text']) {
            $member->assertText($config['text']);
        }

        if (null !== $config['text_group_id']) {
            $member->assertTextGroupId($config['text']);
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
            $asserted_main,
            $asserted_depended
        );

        foreach ($this->getMorphologyMatchings() as $asserted_matching) {
            $link->addAssertedMatching($asserted_matching);
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
                throw new \RuntimeException("unsupported morphology for main chast_rechi = {$this->depended['chast_rechi']} " . var_export($morphology_matching, 1));
            }

            if (empty(MorphologyRegistry::getBaseClasses()[$morphology_matching][$this->depended['chast_rechi']])) {
                throw new \RuntimeException("unsupported morphology for depended chast_rechi = {$this->depended['chast_rechi']} " . var_export($morphology_matching, 1));
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
}





































