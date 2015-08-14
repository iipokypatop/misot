<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 17:25
 */
require $imports['InputText']['form'];

require __DIR__ . '/Rules/form.php';

$post['form']['rule_options']['sigma'] = isset($_POST['form']['rule_options']['sigma']) ? intval($_POST['form']['rule_options']['sigma']) : 101;
$post['form']['rule_options']['submit'] = isset($_POST['form']['rule_options']['submit']);