<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.07.2015
 * Time: 14:02
 */

namespace Aot\Text;


class GroupIdRegistry
{
    const BIT = 1;
    const NIKTO = 2;
    const PRITYAZHATELNIE_1_AND_2_LITSO = 3;

    /**
     * @return array[] слова в нижнем регистре
     */
    public static function getWordVariants()
    {
        return [
            static::BIT => ['быть', 'был', 'была', 'есть', 'есть', 'есть', 'есть', 'есть', 'будь', 'будьте', 'есть', 'были', 'было'], // нижний регистр
            static::NIKTO => ['никто', 'ничто'], // нижний регистр
            static::PRITYAZHATELNIE_1_AND_2_LITSO => ['мой', 'моя', 'моё', 'мои', 'наш', 'наша', 'наше', 'наши', 'твой', 'твоя', 'твое', 'твои', 'ваш', 'ваша', 'ваше', 'ваши',], // нижний регистр

        ];
    }

    public static function getNames()
    {
        return [
            static::BIT => 'глагол быть',
            static::NIKTO => 'никто ничто',
            static::PRITYAZHATELNIE_1_AND_2_LITSO => 'притяжательные местоимения 2 лицо',
        ];
    }
}