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

    public static function defaultTrimUnitType()
    {
        return [
            \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE,
            \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_OTHER,
        ];
    }
}