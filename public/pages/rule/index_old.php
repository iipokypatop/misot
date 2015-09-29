<?php

echo "<pre>", var_export($_POST, 1), "</pre>";

$mainView = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\View::create();

$dependedView = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\View::create();

$linkView = \Aot\Sviaz\Rule\Builder\View::create();


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
