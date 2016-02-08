<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 13:00
 */

namespace Aot\Text\TextParserByTokenizer;


class UnitingPatterns
{
    const WORD_WITH_DASH = 'W(DW)+';

    public static function getUnitingPatterns()
    {
        return [
            self::WORD_WITH_DASH,
        ];
    }

    /**
     * @param string $pattern
     * @return bool
     */
    public static function isUniting($pattern)
    {
        assert(is_string($pattern));
        return in_array($pattern, self::getUnitingPatterns(), true);
    }
}