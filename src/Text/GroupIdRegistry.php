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
    const UKAZATELNIE_MESTOIMENIYA = 4;

    /**
     * @return array[] слова в нижнем регистре
     */
    public static function getWordVariants()
    {
        return [
            static::BIT => ['быть', 'был', 'была', 'есть', 'есть', 'есть', 'есть', 'есть', 'будь', 'будьте', 'есть', 'были', 'было'], // нижний регистр
            static::NIKTO => ['никто', 'ничто'], // нижний регистр
            static::PRITYAZHATELNIE_1_AND_2_LITSO => ['мой', 'моя', 'моё', 'мои', 'наш', 'наша', 'наше', 'наши', 'твой', 'твоя', 'твое', 'твои','твоим', 'ваш', 'ваша', 'ваше', 'ваши', 'их'], // нижний регистр
            static::UKAZATELNIE_MESTOIMENIYA => ['тот', 'та', 'то', 'те', 'этот', 'эта', 'это', 'эти', 'такими', 'какими', 'каков', 'таков', 'какова', 'такова', 'каково', 'таково', 'каковы', 'таковы', 'такой', 'такая', 'такое', 'такие', 'какой', 'какая', 'какое', 'какие'], // нижний регистр

        ];
    }

    public static function getNames()
    {
        return [
            static::BIT => 'глагол быть',
            static::NIKTO => 'никто ничто',
            static::PRITYAZHATELNIE_1_AND_2_LITSO => 'притяжательные местоимения 2 лицо',
            static::UKAZATELNIE_MESTOIMENIYA => 'указательные местоимения',
        ];
    }
}