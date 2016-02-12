<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 13:00
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode\Token;
use Aot\Text\TextParserByTokenizer\PseudoCode\Token;

/**
 * Шаблоны объединения Unit'ов представленных в виде псевдокода
 * Class UnitingPatterns
 */
class TokenUnitingPatterns
{
    const WORD_WITH_DASH = 'W(DW)+'; // слова через дефис
    const ELLIPSIS = 'PPP'; // троеточие
    const STUCK_TOGETHER_WORDS = 'W{2,}'; // слепленные слова
    const MANY_SPACES = 'S{2,}'; // слепленные пробелы


    const DELIMITER = '/';
    const MODIFIERS = 'u';
    const REPLACED_SYMBOL = '0';


    /**
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\TokenUnitingPatterns
     */
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
     * @return int[]
     */
    public static function getConformityBetweenUnitingPatternsAndUnitType()
    {
        return [
            static::WORD_WITH_DASH => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD,
            static::ELLIPSIS => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_PUNCTUATION,
            static::STUCK_TOGETHER_WORDS => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD,
            static::MANY_SPACES => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE,
        ];
    }

    /**
     * @param string $pseudo_code
     * @return  \Aot\Text\TextParserByTokenizer\PseudoCode\Token\TokenFoundPatterns[]
     */
    public function findEntryPatterns($pseudo_code)
    {
        assert(is_string($pseudo_code));

        $found_patterns = [];
        foreach (static::getUnitingPatterns() as $pattern) {

            $matches_all = $this->getMatchesByPattern($pattern, $pseudo_code);

            foreach ($matches_all as $matches) {

                $count_units = mb_strlen($matches[0],'utf-8');
                $start_id = $matches[1];

                $pseudo_code = substr_replace(
                    $pseudo_code,
                    str_repeat(static::REPLACED_SYMBOL, $count_units),
                    $start_id,
                    $count_units
                );

                $found_patterns[] = Token\TokenFoundPatterns::create(
                    $start_id,
                    $start_id + $count_units - 1,
                    $this->getUnitTypeByPattern($pattern)
                );

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
        return static::DELIMITER . $pattern . static::DELIMITER . static::MODIFIERS;
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

    /**
     * @param string $pattern
     * @return int
     */
    protected function getUnitTypeByPattern($pattern)
    {
        if (!array_key_exists($pattern, static::getConformityBetweenUnitingPatternsAndUnitType())) {
            throw new \LogicException("The conformity for the pattern " . $pattern . " is not declared");
        }

        return static::getConformityBetweenUnitingPatternsAndUnitType()[$pattern];
    }


}