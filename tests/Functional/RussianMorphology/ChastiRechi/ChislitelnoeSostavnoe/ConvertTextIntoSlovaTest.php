<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe;


class ConvertTextIntoSlovaTest extends \AotTest\AotDataStorage
{

    public function testDifferentChislitelnie()
    {
        $text = <<<EOF
            Я купил 200 яблок.
            Я купил двести яблок.
            Я купил двести двадцать три яблока.
            Я купил миллион яблок и триллион груш.
            Я купил 189 яблок, 869 груш, сто пять апельсинов.
EOF;

        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);


        $this->assertEquals(200, $sentences[0]->getSlova()[2][0]->getDigitalView());
        $this->assertEquals("двести", $sentences[0]->getSlova()[2][0]->getText());

        $this->assertEquals(200, $sentences[1]->getSlova()[2][0]->getDigitalView());
        $this->assertEquals("двести", $sentences[1]->getSlova()[2][0]->getText());

        $this->assertEquals(223, $sentences[2]->getSlova()[2][0]->getDigitalView());
        $this->assertEquals("двести двадцать три", $sentences[2]->getSlova()[2][0]->getText());

        $this->assertEquals(1000000, $sentences[3]->getSlova()[2][1]->getDigitalView());
        $this->assertEquals("миллион", $sentences[3]->getSlova()[2][1]->getText());

        $this->assertEquals(189, $sentences[4]->getSlova()[2][0]->getDigitalView());
        $this->assertEquals("сто восемьдесят девять", $sentences[4]->getSlova()[2][0]->getText());

    }

}