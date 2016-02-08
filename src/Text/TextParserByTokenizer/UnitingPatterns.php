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
    const WORD_WITH_DASH = 'W(DW)+'; // слово-слово
    const ELLIPSIS = 'PPP'; // троеточие

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
     * @param string $pseudo_code
     * @return int[][]
     */
    public static function findEntryPatterns($pseudo_code)
    {
        assert(is_string($pseudo_code));

        $found_patterns = [];
        foreach (self::getUnitingPatterns() as $pattern) {
            if (preg_match_all('/' . $pattern . '/', $pseudo_code, $matches_all, PREG_OFFSET_CAPTURE)) {
                foreach ($matches_all[0] as $matches) {

                    $count_units = strlen($matches[0]);
                    $start_id = $matches[1];

                    $found_patterns[] = [
                        'start_id' => $start_id,
                        'end_id' => $start_id + $count_units - 1,
                    ];
                }
            }
        }

        return $found_patterns;
    }
}