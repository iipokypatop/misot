<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\PostProcessors;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;


class BidirectionalTest extends \AotTest\AotDataStorage
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
        $ob = \Aot\Sviaz\PostProcessors\Bidirectional::create();

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
        $ob = $this->getMock(\Aot\Sviaz\PostProcessors\Bidirectional::class, [
            'alertUser',
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
            ->method('alertUser')
            ->with([$sviazi[1], $sviazi[3], $sviazi[4]]);

        $ob->run($sviazi);

    }
}