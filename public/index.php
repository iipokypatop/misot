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

\Aot\Publisher\Base::get()->publish();

ini_set('display_errors', 1);
error_reporting(E_ALL);


$POST = [];
$GET = [];

$GET['area'] =
    isset($_GET['area'])
        ? strtolower($_GET['area'])
        : '';


if (!empty($GET['area'])) {

    switch ($GET['area']) {
        case 'rule' :
            require __DIR__ . '/pages/rule/index.php';
            break;
        default:
            die('unknown area');
    }
} else if (\Aot\Publisher\Base::get()->isPublished($_SERVER['REQUEST_URI'])) {
    echo \Aot\Publisher\Base::get()->getCode($_SERVER['REQUEST_URI']);

} else {

    die('page not found');
}

