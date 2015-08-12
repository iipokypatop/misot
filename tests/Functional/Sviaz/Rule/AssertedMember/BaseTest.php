<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 14:53
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry;
use Aot\Text\GroupIdRegistry;
use MivarTest\PHPUnitHelper;


use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedLink\Builder\Base as AssertedLinkBuilder;

use Aot\Sviaz\Role\Registry as RoleRegistry;

class BaseTest extends \AotTest\AotDataStorage
{
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

    public function testDao()
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

//        $rule->getDao()
//        $rule->getEntityManager()->persist($rule->getDao());
        $link = $rule->getLinks()[0];

//        $link->getEntityManager()->persist($link->getDao());

//        $rule->getEntityManager()->persist($rule->getDao());

//        $link->getEntityManager()->flush();
        $link->save();
//        $rule_dao = new \AotPersistence\Entities\Rule();
    }
}