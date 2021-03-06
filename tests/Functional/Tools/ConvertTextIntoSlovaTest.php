<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


class ConvertTextIntoSlovaTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $text = "Пичаль, кот пришёл домой. А потом и пёс.";
        $res = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        foreach ($res as $sentence) {
            foreach ($sentence as $key => $value) {
                //Пример, как достать слова
                //print_r($key."\n");
                //print_r($value);
            }
        }
    }

    public function testConvert_ReturnsCorrectFormatAndWorksProperly()
    {
        $words = [
            0 => "Пичаль",
            1 => ",",
            2 => "кот",
            3 => "пришёл",
            4 => "домой",
            5 => ".",
            6 => "А",
            7 => "потом",
            8 => "и",
            9 => "пёс",
            10 => "."
        ];
        $text = "{$words[0]}{$words[1]} {$words[2]} {$words[3]} {$words[4]}{$words[5]} {$words[6]} {$words[7]} {$words[8]} {$words[9]}{$words[10]}";
        $res = \Aot\Tools\ConvertTextIntoSlova::convert($text);

        $this->assertEquals(true, is_array($res));
        $i = 0;
        foreach ($res as $sentence) {
            $this->assertInstanceOf(\Aot\Tools\SentenceContainingVariantsSlov::class, $sentence);
            $this->assertGreaterThan(0, count($sentence));
            foreach ($sentence as $key => $value) {
                $this->assertEquals($words[$i], $key);
                foreach ($value as $slovo) {
                    $this->assertInstanceOf(\Aot\Unit::class, $slovo);
                }
                $i++;
            }
        }
    }


}