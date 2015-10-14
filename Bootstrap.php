<?php

define('PROJECT_ROOT', __DIR__);

require_once __DIR__ . "/vendor/autoload.php";


class Dw extends \Aot\MivarTextSemantic\Dw
{
}

class PointWdw extends \Aot\MivarTextSemantic\PointWdw
{
}

class MorphAttribute extends \Aot\MivarTextSemantic\MorphAttribute
{
}


\Overloader\Base::load([
    'txt',
    'mivar-projects',
]);

ini_set('display_errors', 1);
error_reporting(E_ALL);



/*if (!defined('LIB_DIR')) {
    if (is_dir(__DIR__ . '/../mivar_text_semantic/lib/')) {
        define('LIB_DIR', __DIR__ . '/../mivar_text_semantic/lib/');
    } elseif (__DIR__ . '/vendor/txt/mivar_text_semantic/lib/') {
        define('LIB_DIR', __DIR__ . '/vendor/txt/mivar_text_semantic/lib/');
    }
}*/