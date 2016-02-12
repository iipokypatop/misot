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
$parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
$parser->run($text);
$units = $parser->getUnits();

echo "<div class='table-responsive'>";
echo "<table class='table table-bordered table-condensed'>";
echo "<tr class='success'><td>#</td><td>Элементы</td></tr>";
foreach ($parser->getSentences() as $sentence_id => $sentence) {

    $sentence_id++;
    echo "<tr class='info'><td colspan='2' >$sentence</td></tr>";
    $i = 1;
    foreach ($sentence->getUnits() as $unit) {
        if (trim($unit) === '') {
            continue;
        }
        echo "<tr><td>$i</td><td>$unit</td></tr>";
        $i++;
    }
}
echo "</table>";
echo "</div>";
die;