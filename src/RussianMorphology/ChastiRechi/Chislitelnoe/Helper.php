<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 10.05.2016
 * Time: 18:28
 */

namespace Aot\RussianMorphology\ChastiRechi\Chislitelnoe;


class Helper
{


    public static function convertToString($digital_value)
    {
        //TODO Реализовать
        assert(is_numeric($digital_value));
        
        return 'число_' . $digital_value;
    }


    /**
     * @param string $string
     * @return int
     */
    public static function convertToDigital($string)
    {
        //TODO РЕАЛИЗОВАТЬ

        $map = [

            'двадцать' => 20,
            'тридцать' => 30,
            'сорок' => 40,
            'пятьдесят' => 50,
            'шестьдесят' => 60,
            'семьдесят' => 70,
            'восемьдесят' => 80,
            'девяносто' => 90,


            'сто' => 100,
            'двести' => 200,
            'триста' => 300,
            'четыреста' => 400,
            'пятьсот' => 500,
            'шестьсот' => 600,
            'семьсот' => 700,
            'восемьсот' => 800,
            'девятьсот' => 900,

            'тысяча' => 1000,
            'тысячи' => 1000,
            'тысяч' => 1000,

            'ноль' => 0,
            'один' => 1,
            'два' => 2,
            'три' => 3,
            'четыре' => 4,
            'пять' => 5,
            'шесть' => 6,
            'семь' => 7,
            'восемь' => 8,
            'девять' => 9,
            'десять' => 10,


        ];

        foreach ($map as $pattern => $value) {
            $string = preg_replace("/$pattern/ui", $value, $string);
        }

        $parts = preg_split('/[\s]+/', $string);

        $summ = 0;
        foreach ($parts as $part) {
            $summ += $part;
        }


        return $summ;
    }


}