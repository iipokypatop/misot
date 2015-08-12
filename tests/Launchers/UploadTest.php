<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/08/15
 * Time: 17:16
 */

namespace AotTest\Functional\RussianMorphology;



class UploadTest extends \AotTest\AotDataStorage
{

    public function testLaunch(){

        $this->markTestSkipped('Скрипт для наполнения базы реестрами. Пропускаем...');
        # chastiRechi
        $registry = new \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry();
        $registry->save();

        # morphology
        $registry = new \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry();
        $registry->save();

        # typeLink
        $registry = new \Aot\Sviaz\Podchinitrelnaya\Registry();
        $registry->save();

        # role
        $registry = new \Aot\Sviaz\Role\Registry();
        $registry->save();

        #linkChecker
        $registry = new \Aot\Sviaz\Rule\AssertedLink\Checker\Registry();
        $registry->save();

        #memberChecker
        $registry = new \Aot\Sviaz\Rule\AssertedMember\Checker\Registry();
        $registry->save();

        #textGroup
        $registry = new \Aot\Text\GroupIdRegistry();
        $registry->save();

        #position (для Third)
        $registry = new \Aot\Sviaz\Rule\AssertedMember\PositionRegistry();
        $registry->save();

        #presence (для Third)
        $registry = new \Aot\Sviaz\Rule\AssertedMember\PresenceRegistry();
        $registry->save();

        #operator
        $registry = new \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\OperatorRegistry();
        $registry->save();
    }
}