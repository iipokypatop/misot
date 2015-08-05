<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 13:16
 */


$imports = [
    'InputText' => [
        'view' => __DIR__ . '/../InputText/ctrl.php',
        'model' => __DIR__ . '/../InputText/model.php',
        'form' => __DIR__ . '/../InputText/form.php',
    ],
    'ShowParseResult' => [
        'view' => __DIR__ . '/../ShowParseResult/ctrl.php',
        'model' => __DIR__ . '/../ShowParseResult/model.php',
        'form' => __DIR__ . '/../ShowParseResult/form.php',
    ]
];

require __DIR__ . '/' . 'form.php';
require __DIR__ . '/' . 'model.php';
require __DIR__ . '/' . 'view.phtml';

