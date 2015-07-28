<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 12:15
 */

namespace Aot\Text\TextParser;


class PatternsRegistry
{

    public static function getClassesOld()
    {
        return [
            \Aot\Text\TextParser\Replacement\FIO::class => [
                "/[А-Я][а-я]+\\s+[А-Я]\\.\\s*[А-Я]\\./u",
                "/[А-Я]\\.\\s*[А-Я]\\.\\s*[А-Я][а-я]+/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceHooks::class => [
                "/[\\[\\]]/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceNumbers::class => [
                "/(\\{\\{)?(((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10})/u",
                "/(\\{\\{)?(\\d+([\\s\\,]*\\d+)*)/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceShort::class => [
                "/и\\sт\\.{0,1}[пд]\\.{0,1}/u",
                "/тел./u",
                "/т\\./u",
                "/н\\.?э\\.?/u"
            ]
        ];
    }

    public static function getClasses()
    {
        return [
            \Aot\Text\TextParser\Replacement\FIO::class => [
                "/[А-Я][а-я]+\\s+[А-Я]\\.\\s*[А-Я]\\./u",
                "/[А-Я]\\.\\s*[А-Я]\\.\\s*[А-Я][а-я]+/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceHooks::class => [
                "/[\\[\\]]/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceNumbers::class => [
                "/(\\{\\{)?(((8|\\+7)[\\- ]?)?(\\(?\\d{3}\\)?[\\- ]?)?[\\d\\- ]{7,10})/u",
                "/(\\{\\{)?(\\d+([\\s\\,]*\\d+)*)/u"
            ],
            \Aot\Text\TextParser\Replacement\ReplaceShort::class => [
                "/и\\sт\\.{0,1}[пд]\\.{0,1}/u",
                "/тел./u",
                "/т\\./u",
                "/н\\.?э\\.?/u"
            ]
        ];
    }

    public static function getPatterns($class)
    {
        $classes_patterns = static::getClasses();

        return $classes_patterns[$class];
    }
}