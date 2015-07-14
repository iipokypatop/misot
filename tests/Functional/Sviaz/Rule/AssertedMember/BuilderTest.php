<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 13.07.2015
 * Time: 15:27
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;
use Aot\Sviaz\Rule\AssertedMember\Builder as AssertedMemberBuilder;

class BuilderTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $builder =
            AssertedMemberBuilder::main(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            )
                ->text("text text");

        $builder->morphology(MorphologyRegistry::PADEJ_DATELNIJ);
        $builder->check(MemberCheckerRegistry::PredlogPeredSlovom);


        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Main::class,
            $builder->get()
        );


        $builder =
            AssertedMemberBuilder::depended(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            )
                ->text("text text");

        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Depended::class,
            $builder->get()
        );


        $builder =
            AssertedMemberBuilder::member(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                AssertedMemberBuilder::POSITION_BEFORE_DEPENDED
            )
                ->text("text text");

        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Depended::class,
            $builder->get()
        );

    }

    public function testMorphologyOk()
    {
        $builder =
            AssertedMemberBuilder::main(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            );

        $builder->morphology(MorphologyRegistry::PADEJ_DATELNIJ);

        $this->assertEquals(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
            $builder->get()->getAssertedMorphologiesClasses()[0]
        );

    }

    public function testMorphology_throws_exception()
    {
        $builder =
            AssertedMemberBuilder::main(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            );

        $id = -1;

        try {

            $builder->morphology($id);

            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals(
                "unsupported morphology id = " . var_export($id, 1),
                $e->getMessage()
            );
        }


        $id = MorphologyRegistry::PEREHODNOST_PEREHODNII;

        $chast_rechi_id = ChastiRechiRegistry::SUSCHESTVITELNOE;

        try {

            $builder->morphology($id);

            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals(
                "unsupported morphology id = $id for chast rechi_id = $chast_rechi_id",
                $e->getMessage()
            );
        }
    }
}
































