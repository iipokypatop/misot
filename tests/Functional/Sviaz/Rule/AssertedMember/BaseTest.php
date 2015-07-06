<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 14:53
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember;


use MivarTest\Base;

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

}