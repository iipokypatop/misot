<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 17:31
 */


require $imports['ShowParseResult']['form'];

$post['form']['correct_sequence']['id'] =
    isset($_POST['form']['correct_sequence']['id'])
        ? intval($_POST['form']['correct_sequence']['id'])
        : null;

$post['form']['correct_sequence']['submit'] = isset($_POST['form']['correct_sequence']['submit']);


//var_export($post);die;

