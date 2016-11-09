<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.05.2016
 * Time: 16:49
 */
class BaseTest extends \AotTest\AotDataStorage
{

    public function testConvertComplexToString(){

        $number = '58.58';
        $res = \Aot\Tools\ConverterOfNumeral\Base::convertToString((double)$number);
//        $this->assertEquals($string, \Aot\Tools\ConverterOfNumeral\Base::convertToString((double)$number));

//        $word_processor = \Aot\RussianMorphology\Factory2\WordProcessor::create();
//        $numbers_array = [$number];
//        $word_processor->processDigitalOfNumber($numbers_array);
    }
    /**
     * @dataProvider providerConvertToString
     */
    public function testConvertToString($number, $string)
    {
        $this->assertEquals($string, \Aot\Tools\ConverterOfNumeral\Base::convertToString((double)$number));
    }

    public function providerConvertToString()
    {
        return [
            [2, 'два'],
            [20, 'двадцать'],
            [1945, 'одна тысяча девятьсот сорок пять'],
            [2001, 'две тысячи один'],
            [1234567, 'один миллион двести тридцать четыре тысячи пятьсот шестьдесят семь'],
//            ['58.58', 'один миллион двести тридцать четыре тысячи пятьсот шестьдесят семь'],
        ];
    }

    public function testconvertToDigital()
    {
        $string_1 = "один миллион двести тридцать четыре тысячи пятьсот шестьдесят сёмь";
        $string_2 = "сорок четвертый";
        $string_3 = "Миллион сорок";
        $number_1 = \Aot\Tools\ConverterOfNumeral\Base::convertToDigital($string_1);
        $number_2 = \Aot\Tools\ConverterOfNumeral\Base::convertToDigital($string_2);
        $number_3 = \Aot\Tools\ConverterOfNumeral\Base::convertToDigital($string_3);
        $this->assertEquals(1234567, $number_1);
        $this->assertEquals(44, $number_2);
        $this->assertEquals(1000040, $number_3);
    }
}
