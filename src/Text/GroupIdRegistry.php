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

    /**
     * @return array[] слова в нижнем регистре
     */
    public static function getWordVariants()
    {
        return [
            static::BIT => ['быть','был','была','есть','есть','есть','есть','есть','будь','будьте','есть','были','было'] // нижний регистр
        ];
    }
}