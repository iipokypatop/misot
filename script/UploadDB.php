<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.11.2015
 * Time: 13:08
 */

require_once "../Bootstrap.php";

class UploadDB extends \Aot\Script\Base
{
    public static function run()
    {
        try
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

            return true;
        }
        catch(Exception $e)
        {
            return $e;
        }

    }
}

if (\UploadDB::run()) {
    print_r("Done!");
}
else{
    print_r(var_export($e,1));
}

