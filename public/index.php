<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 11:03
 */

header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . "/../vendor/autoload.php";

ini_set('display_errors', 1);

error_reporting(E_ALL);



if (empty($_GET['area'])) {
    die('undefined area');
}


switch ($_GET['area']) {
    case 'rule' :
        require __DIR__ . '/pages/rule/index.php';
        break;
    default:
        die('unknown area');

}