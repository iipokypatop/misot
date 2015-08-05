<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 11:03
 */

mb_internal_encoding('utf-8');

header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/../Bootstrap.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (empty($_GET['area'])) {
    die('undefined area');
}

$POST = [];
$GET = [];

$GET = isset($_GET['area']) ? $_GET['area'] : '';

$area = strtolower($GET['area']);

switch ($area) {
    case 'rule' :
        require __DIR__ . '/pages/rule/index.php';
        break;
    default:
        die('unknown area');

}