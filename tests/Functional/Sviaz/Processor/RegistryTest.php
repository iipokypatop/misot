<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 3:48
 */

namespace AotTest\Functional\Sviaz\Processor;


use MivarTest\PHPUnitHelper;

class RegistryTest extends \AotTest\AotDataStorage
{
    public function testRegisterMember()
    {
        $ob = new \stdClass;

        $registry = \Aot\Sviaz\Processor\ObjectRegistry::create();

        $registry->registerMember($ob);

        $this->assertEquals(
            [spl_object_hash($ob) => $ob],
            PHPUnitHelper::getProtectedProperty($registry, 'members')
        );
    }

    public function testRegisterMember_throws_RuntimeException()
    {
        $ob = new \stdClass;

        $registry = \Aot\Sviaz\Processor\ObjectRegistry::create();


        try {

            $registry->registerMember($ob);
            $registry->registerMember($ob);

        } catch (\RuntimeException $e) {
            $this->assertEquals(\RuntimeException::class, get_class($e));
        }
    }

    public function testFlush()
    {
        $ob = new \stdClass;

        $registry = \Aot\Sviaz\Processor\ObjectRegistry::create();


        $registry->registerMember($ob);

        $registry->clean();

        $this->assertEquals(
            [],
            PHPUnitHelper::getProtectedProperty($registry, 'members')
        );
    }

    public function testGetMemberByHas_throws_RuntimeException()
    {

        $registry = \Aot\Sviaz\Processor\ObjectRegistry::create();


        try {

            $registry->getMemberByHash(md5(1));

            $this->fail();


        } catch (\RuntimeException $e) {

            $this->assertEquals(\RuntimeException::class, get_class($e));
        }
    }
}