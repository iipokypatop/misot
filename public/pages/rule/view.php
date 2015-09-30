<?php


/** @var $mainView \Aot\Sviaz\Rule\AssertedMember\Builder\Main\View */
/** @var $dependedView \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\View */
/** @var $linkView \Aot\Sviaz\Rule\Builder\View */


?><!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="/lib/joint.js"></script>
    <script type="text/javascript" src="/lib/joint.shapes.fsa.js"></script>
    <link rel="stylesheet" href="/lib/joint.css" type="text/css">

    <? if (!empty($script)): ?>
        <script type="text/javascript" src="<?= $script ?>"></script>
    <? endif ?>

</head>

<body>
<div>
    <? require $body ?>
</div>

</body>
</html>