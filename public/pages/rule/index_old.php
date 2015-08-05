<?php

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;


//\Aot\View\Charger::charge($_POST);


echo "<pre>",
var_export($_POST, 1),

"</pre>";


$mainView = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\View::create();

$dependedView = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\View::create();

$linkView = \Aot\Sviaz\Rule\AssertedLink\Builder\View::create();


$id = null;
if (!empty($_GET['id'])) {
    if (preg_match("/\\d+/", $_GET['id'])) {
        $id = intval($_GET['id']);
    }
}

if (is_int($id)) {
    if (empty($_POST)) {
        // read
        // get from db
        trigger_error("no db yet");
    } else {
        // update
        // save
    }
} else {
    if (empty($_POST)) {
        // blank form
    } else {

        // create
    }
}

require __DIR__ . '/view.php';
