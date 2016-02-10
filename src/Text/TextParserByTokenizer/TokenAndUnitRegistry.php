<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 19:07
 */

namespace Aot\Text\TextParserByTokenizer;


class TokenAndUnitRegistry
{
    public static function getAssociatedUnitTypeAndTokenTypeMap()
    {
        return [
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_PUNCTUATION,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_DASH,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_NUMBER,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_OTHER => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_OTHER,
        ];
    }

    /**
     * @param int $token_type
     */
    public static function getUnitTypeByTokenType($token_type)
    {
        if (!array_key_exists($token_type, static::getAssociatedUnitTypeAndTokenTypeMap())) {
            throw new \LogicException("The conformity for the token type " . $token_type . " is not declared");
        }
        return static::getAssociatedUnitTypeAndTokenTypeMap()[$token_type];
    }
}