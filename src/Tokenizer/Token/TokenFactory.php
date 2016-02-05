<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 005, 05, 02, 2016
 * Time: 14:38
 */

namespace Aot\Tokenizer\Token;


class TokenFactory
{
    const TOKEN_TYPE_OTHER = 1;

    const TOKEN_TYPE_WORD = 11;
    const TOKEN_TYPE_NUMBER = 12;


    const TOKEN_TYPE_SPACE = 13;
    const TOKEN_TYPE_PUNCTUATION = 14;

    /**
     * @var int
     */
    protected $token_type;

    /** @var  string */
    protected $token_text;


    public static function getIds()
    {
        return [
            static::TOKEN_TYPE_OTHER,

            static::TOKEN_TYPE_WORD,
            static::TOKEN_TYPE_NUMBER,
            static::TOKEN_TYPE_SPACE,
            static::TOKEN_TYPE_PUNCTUATION,
        ];
    }


    public static function getNames()
    {
        return [
            static::TOKEN_TYPE_OTHER => 'другое',

            static::TOKEN_TYPE_WORD => 'слово',
            static::TOKEN_TYPE_NUMBER => 'число',
            static::TOKEN_TYPE_SPACE => 'пробел',
            static::TOKEN_TYPE_PUNCTUATION => 'пунктуация',
        ];
    }
}