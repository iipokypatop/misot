<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 17:29
 */

$post['form']['text_for_parse']['text'] = isset($_POST['form']['text_for_parse']['text']) ? htmlentities($_POST['form']['text_for_parse']['text']) : '';

$post['form']['text_for_parse']['submit'] = isset($_POST['form']['text_for_parse']['submit']);

