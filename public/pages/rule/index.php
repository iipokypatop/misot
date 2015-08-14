<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 12:23
 */

$action = null;

if (empty($_GET['action'])) {
    die('unknown action');
}

$action = strtolower($_GET['action']);

switch ($action) :

    case strtolower('showRules'):
        $body = __DIR__ . '/ShowRules/ctrl.php';
        $script = '/pages/rule/ShowRules/script.js';
        break;

    case strtolower('showParseResult'):
        $body = __DIR__ . '/ShowParseResult/ctrl.php';
        break;
    case strtolower('Test'):
        $body = __DIR__ . '/Test/ctrl.php';
        $script = '/pages/rule/Test/script.js';
        break;

    case strtolower('inputText'):
    default:
        $body = __DIR__ . '/InputText/ctrl.php';
endswitch;


require __DIR__ . '/view.php';