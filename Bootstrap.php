<?php



require_once __DIR__ . "/vendor/autoload.php";

MivarDevelopmentLoader::load();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if(!defined('LIB_DIR')){
    define('LIB_DIR', __DIR__ . '/../mivar_text_semantic/lib/');
}