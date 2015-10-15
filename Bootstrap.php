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
