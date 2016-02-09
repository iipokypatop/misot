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
    const WORD_WITH_DASH = 'W(DW)+'; // слова через дефис
    const ELLIPSIS = 'PPP'; // троеточие
    const STUCK_TOGETHER_WORDS = 'W{2,}'; // слепленные слова
    const MANY_SPACES = 'S{2,}';
    const START_PATTERN = '/';
    const END_PATTERN = '/';
    const MODIFIERS = 'u'; // слепленные пробелы


    public static function create()
    {
        return new static();
    }

    /**
     * @return string[]
     */
    public static function getUnitingPatterns()
    {
        return [
            static::WORD_WITH_DASH,
            static::ELLIPSIS,
            static::STUCK_TOGETHER_WORDS,
            static::MANY_SPACES,
        ];
    }

    /**
     * @param string $pseudo_code
     * @return int[][]
     */
    public function findEntryPatterns($pseudo_code)
    {
        assert(is_string($pseudo_code));

        $found_patterns = [];
        foreach (static::getUnitingPatterns() as $pattern) {
            $matches_all = $this->getMatchesByPattern($pattern, $pseudo_code);
            foreach ($matches_all as $matches) {

                $count_units = strlen($matches[0]);
                $start_id = $matches[1];

                $found_patterns[] = [
                    'start_id' => $start_id,
                    'end_id' => $start_id + $count_units - 1,
                ];
            }

        }

        return $found_patterns;
    }


    /**
     * @param string $pattern
     * @return string
     */
    protected function getRegularExpression($pattern)
    {
        return static::START_PATTERN . $pattern . static::END_PATTERN . static::MODIFIERS;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return array
     */
    protected function getMatchesByPattern($pattern, $subject)
    {
        preg_match_all($this->getRegularExpression($pattern), $subject, $matches_all, PREG_OFFSET_CAPTURE);
        if (empty($matches_all[0])) {
            return [];
        }
        return $matches_all[0];
    }


}