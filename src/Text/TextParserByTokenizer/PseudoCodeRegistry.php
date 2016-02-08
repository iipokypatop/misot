<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 14:07
 */

namespace Aot\Text\TextParserByTokenizer;


class PseudoCodeRegistry
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
        if (!isset(self::getTokenRegistry()[$type])) {
            throw new \LogicException('Unknown token type: ' . $type);
        }

        return self::getTokenRegistry()[$type];
    }

    /**
     * @return string[]
     */
    public static function getTokenRegistry()
    {
        return [
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER => self::NUMBER,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION => self::PUNCTUATION,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE => self::SPACE,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD => self::WORD,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH => self::DASH,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_OTHER => self::OTHER,
        ];
    }
}