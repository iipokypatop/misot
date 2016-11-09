<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 11.05.2016
 * Time: 16:45
 */

namespace Aot\Tools\ConverterOfNumeral;


class Base
{

    /**
     * @brief большой копипаст с https://habrahabr.ru/sandbox/94515/ , чуть рефакторинга и ещё больше предстоит ещё больший рефакторинг
     *
     * @param double $digital_value
     * @return string
     */
    public static function convertToString($digital_value)
    {
        assert(is_double($digital_value));
        $little_number = \Aot\Tools\ConverterOfNumeral\NumberIntoWordsRegistry::getLittleNumber();
        $large_number = \Aot\Tools\ConverterOfNumeral\NumberIntoWordsRegistry::getLargeNumber();
        $map = \Aot\Tools\ConverterOfNumeral\NumberIntoWordsRegistry::getMap();

        // TODO большой копипаст с https://habrahabr.ru/sandbox/94515/ , чуть рефакторинга и ещё больше предстоит ещё больший рефакторинг
        // обозначаем переменную в которую будем писать сгенерированный текст
        $words_of_result = [];

        // дополняем число нулями слева до количества цифр кратного трем,
        // например 1234, преобразуется в 001234
        $digital_value = str_pad($digital_value, ceil(strlen($digital_value) / 3) * 3, 0, STR_PAD_LEFT);

        // разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
        // т.к. мы не знаем максимальный порядок числа и будем бежать снизу
        // единицы, тысячи, миллионы и т.д.
        $parts = array_reverse(str_split($digital_value, 3));

        // бежим по каждой части
        foreach ($parts as $i => $part) {

            // если часть не равна нулю, нам надо преобразовать ее в текст
            if ($part > 0) {

                // обозначаем переменную в которую будем писать составные числа для текущей части
                $digits = array();

                // если число треххзначное, запоминаем количество сотен
                if ($part > 99) {
                    $digits[] = floor($part / 100) * 100;
                }

                // если последние 2 цифры не равны нулю, продолжаем искать составные числа
                // (данный блок прокомментирую при необходимости)
                if ($mod1 = $part % 100) {
                    $mod2 = $part % 10;
                    // old
                    // $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
                    $flag = $i == 1 && ($mod2 === 1 || $mod2 === 2) ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag * $mod1;
                    } else {
                        $digits[] = floor($mod1 / 10) * 10;
                        $digits[] = $flag * $mod2;
                    }
                }

                // берем последнее составное число, для плюрализации
                $last = abs(end($digits));

                // преобразуем все составные числа в слова
                foreach ($digits as $j => $digit) {
                    $digits[$j] = $little_number[$digit];
                }

                if ($i !== 0) {
                    // добавляем обозначение порядка или валюту
                    $digits[] = $large_number[$i][(($last %= 100) > 4 && $last < 20) ? 2 : $map[min($last % 10, 5)]];
                }

                // объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
                array_unshift($words_of_result, join(' ', $digits));
            }
        }

        // преобразуем переменную в текст и возвращаем из функции, ура!
        return join(' ', $words_of_result);

    }


    /**
     * @param string $string
     * @return double
     */
    public static function convertToDigital($string)
    {
        assert(is_string($string));
        $matches = preg_split('/\s+/ui', $string);

        $cardinal_numbers = \Aot\Tools\ConverterOfNumeral\WordsIntoNumberRegistry::getCardinalnumbers();
        $ordinal_numbers = \Aot\Tools\ConverterOfNumeral\WordsIntoNumberRegistry::getOrdinalNumbers();
        foreach ($matches as $index => $raw_number) {
            $number = static::processRawNumber($raw_number);
            if (array_key_exists($number, $cardinal_numbers)) {
                $matches[$index] = $cardinal_numbers[$number];
                continue;
            }
            if (array_key_exists($number, $ordinal_numbers)) {
                $matches[$index] = $ordinal_numbers[$number];
                continue;
            }
        }

        $sum = 0;
        $tmp_sum = 0;
        foreach ($matches as $number) {
            if ($number < 1000) {
                $tmp_sum += $number;
                continue;
            }
            if ($number >= 1000) {
                $tmp_sum = ($tmp_sum === 0) ? $number : $tmp_sum * $number;
                $sum += $tmp_sum;
                $tmp_sum = 0;
                continue;
            }
        }
        $sum += $tmp_sum;

        return (double)$sum;
    }

    /**
     * @param string $raw_number
     * @return string
     * @throws \Aot\Exception
     */
    protected static function processRawNumber($raw_number)
    {
        $number = mb_strtolower($raw_number, 'UTF-8');
        $number = preg_replace('/ё/ui', 'е', $number);
        if (!is_string($number)) {
            throw new \Aot\Exception("Что-то пошло не так");
        }
        return $number;
    }
}