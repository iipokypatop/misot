<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 13:16
 */


$imports = [
    'InputText' => [
        'view' => __DIR__ . '/../InputText/view.html',
        'model' => __DIR__ . '/../InputText/model.php',
        'form' => __DIR__ . '/../InputText/form.php',
    ]
];


require __DIR__ . '/' . 'form.php';
require __DIR__ . '/' . 'model.php';
require __DIR__ . '/' . 'view.html';