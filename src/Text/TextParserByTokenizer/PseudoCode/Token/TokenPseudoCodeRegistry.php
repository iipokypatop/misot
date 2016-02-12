<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 14:07
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode\Token;


class TokenPseudoCodeRegistry
{

    const NUMBER = 'N';
    const PUNCTUATION = 'P';
    const SPACE = 'S';
    const WORD = 'W';
    const DASH = 'D';
    const OTHER = 'O';

    /**
     * Получить символ псевдокода для токена
     *
     * @param string $type
     * @return string
     */
    public static function getTokenCode($type)
    {
        if (!isset(static::getTokenRegistry()[$type])) {
            throw new \LogicException('Unknown token type: ' . $type);
        }

        return static::getTokenRegistry()[$type];
    }

    /**
     * @return string[]
     */
    public static function getTokenRegistry()
    {
        return [
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER => static::NUMBER,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION => static::PUNCTUATION,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE => static::SPACE,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD => static::WORD,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH => static::DASH,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_OTHER => static::OTHER,
        ];
    }
}