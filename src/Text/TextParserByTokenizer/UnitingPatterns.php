<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 13:00
 */

namespace Aot\Text\TextParserByTokenizer;

/**
 * Шаблоны объединения Unit'ов представленных в виде псевдокода
 * Class UnitingPatterns
 */
class UnitingPatterns
{
    const WORD_WITH_DASH = 'W(DW)+';
    const ELLIPSIS = 'PPP';

    /**
     * @return string[]
     */
    public static function getUnitingPatterns()
    {
        return [
            self::WORD_WITH_DASH,
            self::ELLIPSIS,
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