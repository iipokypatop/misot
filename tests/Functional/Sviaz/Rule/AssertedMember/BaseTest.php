<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 14:53
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember;


use Aot\Persister;
use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry;
use Aot\Sviaz\Rule\Container;
use Aot\Text\GroupIdRegistry;
use Doctrine\ORM\EntityManager;
use MivarTest\PHPUnitHelper;


use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedLink\Builder\Base as AssertedLinkBuilder;

use Aot\Sviaz\Role\Registry as RoleRegistry;

class BaseTest extends \AotTest\AotDataStorage
{
    use Persister;
    public function testAssertTextGroupIdThrowsExceptionAfterDefiningText()
    {

        $text = $this->getUniqueString();

        $member = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $member->assertText($text);

        $this->assertEquals(
            $text,
            $member->getAssertedText()
        );

        $text_group_id = rand();

        try {
            $member->assertTextGroupId($text_group_id);

            $this->fail("Expected Exception " . \RuntimeException::class);

        } catch (\RuntimeException $e) {

            $this->assertInstanceOf(\RuntimeException::class, $e);
        }
    }

    public function testAssertTextThrowsExceptionAfterDefiningTextGroupId()
    {
        $text = $this->getUniqueString();

        $member = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $text_group_id = rand();

        try {
            $member->assertTextGroupId($text_group_id);

            $this->assertEquals(
                $text_group_id,
                $member->getAssertedTextGroupId()
            );

            $member->assertText($text);

            $this->fail("Expected Exception " . \RuntimeException::class);

        } catch (\RuntimeException $e) {

            $this->assertInstanceOf(\RuntimeException::class, $e);
        }
    }

    public function testAddChecker_throws_exception()
    {
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();

        $checker_class = new \stdClass;
        try {
            $depended->addChecker($checker_class);
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals(get_class($e), \RuntimeException::class);
            $this->assertEquals("must be string " . var_export($checker_class, 1), $e->getMessage());
        }


        $checker_class = \stdClass::class;
        try {
            $depended->addChecker($checker_class);
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals(get_class($e), \RuntimeException::class);
            $this->assertEquals("unsupported checker class $checker_class", $e->getMessage());
        }
    }


    public function testAddChecker_returns_void()
    {
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();

        $checker_class = \Aot\Sviaz\Rule\AssertedMember\Checker\PredlogPeredSlovom::class;

        $result = $depended->addChecker($checker_class);

        $this->assertEquals(null, $result);
        $this->assertEquals(
            [$checker_class],
            PHPUnitHelper::getProtectedProperty($depended, 'checker_classes')
        );
    }

    public function testAssertMorphology()
    {
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $reg1 = MorphologyRegistry::PADESZH;
        $reg2 = MorphologyRegistry::PADESZH_IMENITELNIJ;
        $reg3 = ChastiRechiRegistry::SUSCHESTVITELNOE;
        $morphology_class = MorphologyRegistry::getClasses()[$reg1][$reg2][$reg3];
        try {
            // не установили часть речи
            $depended->assertMorphology($morphology_class);
            $this->fail('Не должно быть тут');
        } catch (\RuntimeException $e) {
            $this->assertEquals("asserted_chast_rechi_class is not defined", $e->getMessage());
        }

        // ставим неверную часть речи
        $chast_rechi = ChastiRechiRegistry::getClasses()[ChastiRechiRegistry::GLAGOL];
        $depended->assertChastRechi($chast_rechi);
        try {
            $depended->assertMorphology($morphology_class);
            $this->fail('Не должно быть тут');

        } catch (\RuntimeException $e) {
            $this->assertEquals("chastRechi and priznakClass does not match", $e->getMessage());
        }
    }

    public function testAssertTextGroupId()
    {
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $depended->assertText('test text');
        try {
            $depended->assertTextGroupId(1);
            $this->fail('Не должно быть тут');
        } catch (\RuntimeException $e) {
            $this->assertEquals('asserted_text already defined', $e->getMessage());
        }

        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $fail_id = 666;
        try {
            $depended->assertTextGroupId($fail_id);
            $this->fail('Не должно быть тут');
        } catch (\RuntimeException $e) {
            $this->assertEquals("unsupported group registry id = " . $fail_id, $e->getMessage());
        }
    }

    public function testAssertText()
    {
        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        try {
            $depended->assertText('');
            $this->fail('Не должно быть тут');
        } catch (\RuntimeException $e) {
            $this->assertEquals("asserted_text is empty string", $e->getMessage());
        }

        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();
        $depended->assertTextGroupId(1);
        try {
            $depended->assertText('текст');
            $this->fail('Не должно быть тут');
        } catch (\RuntimeException $e) {
            $this->assertEquals("asserted_text_group_id already defined", $e->getMessage());
        }
    }

    public function testLaunchByDao()
    {
        $this->markTestSkipped();
        // создаем member
        $member_dao = new \AotPersistence\Entities\Member();

        // ставим у него часть речи
        $chastRechi = new \AotPersistence\Entities\ChastiRechi();
        $chastRechi->setName(ChastiRechiRegistry::getNames()[ChastiRechiRegistry::GLAGOL]);
        $member_dao->setChastRechi($chastRechi);

        // устанавливаем у него роль
        $role = new \AotPersistence\Entities\Role();
        $role->setName(Registry::getNames()[Registry::OTNOSHENIE]);
        $member_dao->setRole($role);


        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::createByDao($member_dao);
    }

    public function testSaveDao()
    {
//        $this->markTestSkipped();
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::MESTOIMENIE,
                        RoleRegistry::VESCH
                    )
                        ->morphologyEq(MorphologyRegistry::RAZRYAD_LICHNOE)
                        ->morphologyEq(MorphologyRegistry::PADESZH_IMENITELNIJ)
                        ->textGroupId(GroupIdRegistry::NIKTO)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->check(MemberCheckerRegistry::ChasticaNePeredSlovom)
                        ->text('Какой-то текст')
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::ROD
                        )
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                        ->dependedAfterMain()

                );

        $rule = $builder->get();

        # сохраняем связь в БД
        $link = $rule->getLinks()[0];

//        $matchings = $link->getDao()->getMatchings();
//
//        /** @var \Doctrine\Common\Collections\ArrayCollection $matchings */
//        foreach ($matchings->getIterator() as $matching) {
//
//            /** @var \AotPersistence\Entities\Link $link_entity */
//            $link_entity = $link->getDao();
//
//            /** @var \AotPersistence\Entities\MorphologyMatching $matching */
//            $matching->setLink($link_entity);
//        }

        $link->persist();
        $link->flush();
        #
    }

    public function testSaveRules()
    {
        $this->markTestSkipped();
        $methods = get_class_methods(Container::class);
//        print_r($methods);
        foreach ($methods as $id => $method) {
            // пропускаем старые правила
            if ($id > 5) {
                try {
                    $rule = Container::$method();
                    if( is_array($rule))
                    {
                        foreach ($rule as $rule_item) {

                            $link = $rule_item->getLinks()[0];

                            $link->persist();
                            $link->flush();
                        }

                    }
                    else{

                        $link = $rule->getLinks()[0];

                        $link->persist();
                        $link->flush();
                    }
                } catch (\RuntimeException $e) {
//                    print_r([$id => $e->getMessage()]);
                }
            }
//            print_r($rule);
        }
    }

    public function testLoadRules()
    {
        $this->markTestSkipped();
        $em = $this->getEntityManager();
        /** @var \AotPersistence\Entities\Rule $rule_entity */
        $rule_entity = $em->find(\AotPersistence\Entities\Rule::class, 115);

        $rule = \Aot\Sviaz\Rule\Base::createByDao($rule_entity);
        $main = $rule->getAssertedMain();
        $role = $main->getRoleClass();
//        print_r($main->getDao()->getId());
//        $rule->getAssertedMain()->get();

    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        // TODO: Implement getEntityClass() method.
    }
}