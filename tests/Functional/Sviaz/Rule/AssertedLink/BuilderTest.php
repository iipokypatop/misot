<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 14.07.2015
 * Time: 12:42
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedLink;


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
        $builder = \Aot\Sviaz\Rule\AssertedLink\Builder::create()
            ->check(\Aot\Sviaz\Rule\AssertedLink\Checker\Registry::DependedAfterMain);

        $builder ->morphologyMatching(MorphologyRegistry::PADESZH);

        $rule = \Aot\Sviaz\Rule\Base::create(
            $this->getAssertedMemberBuilder_main()->get(),
            $this->getAssertedMemberBuilder_depended()->get()
        );


        $link = $builder->check(
            \Aot\Sviaz\Rule\AssertedLink\Checker\Registry::NetSuschestvitelnogoVImenitelnomPadeszhe
        );

        $link = $builder->get($rule);

        //print_r($link);
    }

    public function testFind_Throws_Exception()
    {
        $builder = \Aot\Sviaz\Rule\AssertedLink\Builder::create();

        $id = -1;

        try {

            $builder->find($id);

            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals(
                "no finders implemented yet",
                $e->getMessage()
            );
        }

        $id = -1;

        try {

            $builder->check($id);

            $this->fail();

        } catch (\RuntimeException $e) {
            $this->assertEquals(
                "unsupported checker id " . var_export($id, 1),
                $e->getMessage()
            );
        }




    }
}