<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.05.2016
 * Time: 15:20
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe;


class BuildGraphWithChislitelnoeTest extends \PHPUnit_Framework_TestCase
{
    
    public function testRunByWords()
    {
        $this->markTestSkipped("Пример, как строить граф по тексту с использованием составных числительных");

        $text = <<<EOF
            Я купил 200 яблок.
            Я купил двести яблок.
            Я купил двести двадцать три яблока.
            Я купил миллион яблок и триллион груш.
            Я купил 189 яблок, 869 груш, сто пять апельсинов.
EOF;

        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        $graphs = [];
        foreach ($sentences as $sentence) {
            $graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
            $words = [];
            foreach ($sentence->getSlova() as $index => $item) {
                $slovo = $item[0];
                if ($slovo instanceof \Aot\RussianMorphology\Slovo) {
                    if ($slovo instanceof \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base || $slovo instanceof \Aot\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe\Base) {
                        $words[$index] = (string)$slovo->getDigitalView();
                        continue;
                    }
                    $words[$index] = $slovo->getText();
                }
            }

            $graphs[] = $graph->runBySentenceWords($words);
        }

    }
}
