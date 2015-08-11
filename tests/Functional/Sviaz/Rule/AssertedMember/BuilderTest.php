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
use Aot\Sviaz\Rule\AssertedMember\Builder\Base as AssertedMemberBuilder;

class BuilderTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $main_builder = $this->getAssertedMemberBuilder_main();


        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Main::class,
            $main_builder->get()
        );

        $depended_builder = $this->getAssertedMemberBuilder_depended();


        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Depended::class,
            $depended_builder->get()
        );

        $builder_member = $this->getAssertedMemberBuilder_member();

        $this->assertInstanceOf(
            \Aot\Sviaz\Rule\AssertedMember\Third::class,
            $builder_member->get()
        );

    }


    public function testMorphologyOk()
    {
        $builder =
            \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            );

        $builder->morphologyEq(MorphologyRegistry::PADESZH_DATELNIJ);

        $this->assertEquals(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij::class,
            $builder->get()->getAssertedMorphologiesClasses()[0]
        );

    }

    public function testMorphology_throws_exception()
    {
        $builder =
            \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            );

        $id = -1;

        try {

            $builder->morphologyEq($id);

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

            $builder->morphologyEq($id);

            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals(
                "unsupported morphology id = $id for chast rechi_id = $chast_rechi_id",
                $e->getMessage()
            );
        }
    }
}
