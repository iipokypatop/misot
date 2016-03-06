<?php

define('PROJECT_ROOT', __DIR__);

require_once __DIR__ . "/vendor/autoload.php";


\Overloader\Overloader::overload([
    'txt',
    'mivar-projects',
    'doctrine',
]);

ini_set('display_errors', 1);
error_reporting(E_ALL);