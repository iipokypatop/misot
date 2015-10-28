<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.08.2015
 * Time: 13:19
 */
/** @var $post [] */
require $imports['ShowParseResult']['model'];


$processor = \Aot\Sviaz\Processor::create();

$sequences = $processor->go(
    $normalized_matrix,
    []
);

