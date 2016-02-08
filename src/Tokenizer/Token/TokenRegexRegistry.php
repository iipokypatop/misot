<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 005, 05, 02, 2016
 * Time: 14:33
 */

namespace Aot\Tokenizer\Token;


class TokenRegexRegistry
{
    const REGEX_PLUS = '+';
    const REGEX_STRING_START = '^';


    const PATTERN_LETTER = '\p{L}'; // Буква	Включает следующие свойства: Ll, Lm, Lo, Lt и Lu.
    const PATTERN_NUMBER = '\p{N}'; // Число


//      const PATTERN_ =  '/\p{C}/'; // Другое
//      const PATTERN_ =  '/\p{Cc}/'; // Control
//      const PATTERN_ =  '/\p{Cf}/'; // Формат
//      const PATTERN_ =  '/\p{Cn}/'; // Не присвоено
//      const PATTERN_ =  '/\p{Co}/'; // Частное использование
//      const PATTERN_ =  '/\p{Cs}/'; // Суррогат
//
//      const PATTERN_ =  '/\p{Ll}/'; // Строчная буква
//      const PATTERN_ =  '/\p{Lm}/'; // Модификатор буквы
//      const PATTERN_ =  '/\p{Lo}/'; // Другая буква
//      const PATTERN_ =  '/\p{Lt}/'; // Заглавная буква
//      const PATTERN_ =  '/\p{Lu}/'; // Прописная буква
//      const PATTERN_ =  '/\p{M}/'; // Знак
//      const PATTERN_ =  '/\p{Mc}/'; // Пробельный знак
//      const PATTERN_ =  '/\p{Me}/'; // Окружающий знак
    const PATTERN_SPACE = '\s'; // Пробельный знак1
//      const PATTERN_ =  '/\p{Mn}/'; // Не пробельный знак
//
//      const PATTERN_ =  '/\p{Nd}/'; // Десятичное число
//      const PATTERN_ =  '/\p{Nl}/'; // Буквенное число
//      const PATTERN_ =  '/\p{No}/'; // Другое число
    const PATTERN_DASH = '\p{Pd}'; // Знаки тире
    const PATTERN_PUNCTUATION = '\p{P}'; // Пунктуация
//      const PATTERN_ =  '/\p{Pc}/'; // Соединяющая пунктуация
//      const PATTERN_ =  '/\p{Pe}/'; // Закрывающая пунктуация
//      const PATTERN_ =  '/\p{Pf}/'; // Заключительная пунктуация
//      const PATTERN_ =  '/\p{Pi}/'; // Начальная пунктуация
//      const PATTERN_ =  '/\p{Po}/'; // Другая пунктуация
//      const PATTERN_ =  '/\p{Ps}/'; // Открывающая пунктуация
//      const PATTERN_ =  '/\p{S}/'; // Символ
//      const PATTERN_ =  '/\p{Sc}/'; // Денежный знак
//      const PATTERN_ =  '/\p{Sk}/'; // Модификатор символа
//      const PATTERN_ =  '/\p{Sm}/'; // Математический символ
//      const PATTERN_ =  '/\p{So}/'; // Другой символ
//      const PATTERN_ =  '/\p{Z}/'; // Разделитель
//      const PATTERN_ =  '/\p{Zl}/'; // Разделитель строки
//      const PATTERN_ =  '/\p{Zp}/'; // Разделитель абзаца
//      const PATTERN_ =  '/\p{Zs}/'; // Пробельный разделитель

    protected static $registry = [];

    /**
     * @param int $id
     * @return \Aot\Tokenizer\Token\Regex[]
     */
    public static function getTokenTypesByPattern($id)
    {
        if (empty(static::getRegistry()[$id])) {
            return [];
        }

        $patterns = static::getRegistry()[$id];

        $regex_list = [];
        foreach ($patterns as $pattern) {
            $regex_list[] = \Aot\Tokenizer\Token\Regex::create($pattern);
        }

        return $regex_list;
    }

    /**
     * @return \Aot\Tokenizer\Token\Regex[]
     */
    protected static function getRegistry()
    {
        return [
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD => [
                static::PATTERN_LETTER . static::REGEX_PLUS,
            ],

            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER => [
                static::PATTERN_NUMBER . static::REGEX_PLUS,
            ],

            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE => [
                static::PATTERN_SPACE
            ],

            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH => [
                static::PATTERN_DASH
            ],

            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION => [
                static::PATTERN_PUNCTUATION
            ],

        ];
    }
}

