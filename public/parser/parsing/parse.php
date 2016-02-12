<?php

require_once __DIR__ . "/../../../Bootstrap.php";
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 12/02/16
 * Time: 16:58
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die;
}

$text = $_POST['text'];
$t1 = microtime(true);
$parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
$parser->run($text);
$parsing_time = microtime(true) - $t1;
$parsing_time = substr_replace($parsing_time, '', 4, strlen($parsing_time));
$units = $parser->getUnits();

$count_sentences = count($parser->getSentences());
$count_elements = 0;

$table = "<div class='table-responsive'>";
$table .= "<table class='table table-bordered table-condensed table-hover'>";
$table .= "<tr class='success'><td class='strong'>#</td><td>Элементы</td></tr>";
foreach ($parser->getSentences() as $sentence_id => $sentence) {

    $sentence_id++;
    $table .= "<tr class='info'><td colspan='2' >$sentence_id. $sentence</td></tr>";
    $i = 1;
    foreach ($sentence->getUnits() as $unit) {
        if (trim($unit) === '') {
            continue;
        }
        $table .= "<tr><td>$i</td><td>$unit</td></tr>";
        $i++;
    }
    $count_elements += $i - 1;
}
$info = '';
$info .= "<p> Предложений: " . $count_sentences . "</p>";
$info .= "<p> Элементов: " . $count_elements . "</p>";
$info .= "<p> Время обработки: " . $parsing_time . " сек</p>";

$table .= "</table>";
$table .= "</div>";


echo $info . $table;
die;