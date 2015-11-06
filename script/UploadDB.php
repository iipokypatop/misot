<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.11.2015
 * Time: 13:08
 */
namespace Script;

if (!class_exists('\Aot\Script\Base')) {
    require_once __DIR__ . "/../Bootstrap.php";
}

class UploadDB extends \Aot\Script\Base
{
    public static function run()
    {
        # chastiRechi
        $registry = new \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry();
        $registry->save();

        # morphology
        $registry = new \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry();
        $registry->save();

        # typeLink
        $registry = new \Aot\Sviaz\Podchinitrelnaya\Registry();
        $registry->save();

        #linkChecker
        $registry = new \Aot\Sviaz\Rule\Checker\Registry();
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
        $registry = new \Aot\Sviaz\Rule\AssertedMatching\OperatorRegistry();
        $registry->save();

        #role должно быть в БД
        //$registry = new \Aot\Sviaz\Role\Registry();
        //$registry->save();

    }
}

UploadDB::run();
die(0);

