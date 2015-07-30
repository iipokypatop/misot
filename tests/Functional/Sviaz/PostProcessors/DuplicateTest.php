<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\PostProcessors;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedLink\Builder\Base as AssertedLinkBuilder;
use Aot\Text\GroupIdRegistry;


class DuplicateTest extends \AotTest\AotDataStorage
{
    protected static $rule;


    /**
     * This method is called before the first test of this test class is run.
     *
     * @since Method available since Release 3.4.0
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();


        <<<TEXT
      С глаголами образуют связь следующие наречия, которые пишутся через дефис:...
TEXT;


        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::NARECHIE,
                        RoleRegistry::SVOISTVO
                    )
//                        ->textGroupId(GroupIdRegistry::DEFISNARECH_FOR_GL)
                )
                ->link(
                    AssertedLinkBuilder::create()
//                        ->dependedRightBeforeMain()
                );


        static::$rule = $builder->get();
    }

    public function testLaunch()
    {
        $ob = \Aot\Sviaz\PostProcessors\Duplicate::create();

        $raw_sequence = $this->getRawSequence();


        $sviazi [] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[0],
            $raw_sequence[1],
            static::$rule,
            $raw_sequence
        );

        $sviazi [] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[0],
            $raw_sequence[2],
            static::$rule,
            $raw_sequence
        );

        $sviazi [] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[1],
            $raw_sequence[0],
            static::$rule,
            $raw_sequence
        );

        $sviazi [] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[1],
            $raw_sequence[0],
            static::$rule,
            $raw_sequence
        );


        $ob->run($sviazi);
    }


    public function testUserAlerted()
    {
        /** @var \Aot\Sviaz\PostProcessors\Bidirectional | \PHPUnit_Framework_MockObject_MockObject $ob */
        $ob = $this->getMock(\Aot\Sviaz\PostProcessors\Duplicate::class, [
            'choose',
        ]);

        $raw_sequence = $this->getRawSequence();

        $sviazi [1] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[0],
            $raw_sequence[1],
            static::$rule,
            $raw_sequence
        );
        $sviazi [2] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[0],
            $raw_sequence[2],
            static::$rule,
            $raw_sequence
        );
        $sviazi [3] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[1],
            $raw_sequence[0],
            static::$rule,
            $raw_sequence
        );
        $sviazi [4] = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $raw_sequence[1],
            $raw_sequence[0],
            static::$rule,
            $raw_sequence
        );

        $ob
            ->expects($this->once())
            ->method('choose')
            ->with([$sviazi[3], $sviazi[4]])
            ->willReturn($sviazi[3])
        ;

        $result = $ob->run($sviazi);

        $this->assertEquals(
            [$sviazi [1], $sviazi [2], $sviazi [3]],
            $result
        );
    }
}