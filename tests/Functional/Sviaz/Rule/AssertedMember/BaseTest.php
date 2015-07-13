<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 14:53
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember;


use MivarTest\PHPUnitHelper;

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

        $member->assertTextGroupId($text_group_id);

        $this->assertEquals(
            $text_group_id,
            $member->getAssertedTextGroupId()
        );

        try {
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
}