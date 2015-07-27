<?php

use Aot\Sviaz\Rule\AssertedMember\Builder\View;

/** @var $mainView \Aot\Sviaz\Rule\AssertedMember\Builder\Main\View */
/** @var $dependedView \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\View */
/** @var $linkView \Aot\Sviaz\Rule\AssertedLink\Builder\View */


?><!DOCTYPE html>
<html lang="en">
<head>

</head>

<body>
<div>
    <div>
        <form method="post">
            <table border="1" width="100%">
                <tr>
                    <td>
                        <table border="1" width="100%">
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_TEXT_TYPE_NO)->draw() */ ?><!--
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_TEXT_TYPE_TEXT)->draw() */ ?>
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_TEXT_TYPE_TEXT_GROUP)->draw() */ ?>
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_CHAST_RECHI)->draw() */ ?>
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_ROLE)->draw() */ ?>
                                        <tr>
                                            <td>
                                                <? /*= $mainView->getField(View::FIELD_MORPH)->draw() */ ?>   <tr>
                                            <td>
                                                --><? /*= $mainView->getField(View::FIELD_CHECKERS)->draw() */ ?>
                                    </table>
                                </td>
                                <td>

                                </td>
                                <td>123</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                            <tr>
                                <td>
                        </table>
                    </td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                </tr>
            </table>

            <button type="submit">ok</button>

        </form>
    </div>

</div>

</body>
</html>