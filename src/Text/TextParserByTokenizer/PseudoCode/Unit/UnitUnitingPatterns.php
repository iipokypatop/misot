<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 13:00
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode\Unit;

use Aot\Text\Encodings;
use Aot\Text\TextParserByTokenizer\PseudoCode\Unit;

/**
 * Шаблоны объединения Unit'ов представленных в виде псевдокода
 * Class UnitingPatterns
 */
class UnitUnitingPatterns
{
    const START_BRACE = '(';
    const END_BRACE = ')';
    const EXTRA = '?';
    const REG_OR = '|';

    const DELIMITER = '/';
    const MODIFIERS = 'u';
    const REPLACED_SYMBOL = '0';

    /**
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\Unit\UnitUnitingPatterns
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
            static::getFIO(),
            static::getPhoneNumber(),
            static::getOrdinalNumber(),
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

                $count_units = mb_strlen($matches[0], Encodings::UTF_8);
                $start_id = $matches[1];

                $pseudo_code = substr_replace(
                    $pseudo_code,
                    str_repeat(static::REPLACED_SYMBOL, $count_units),
                    $start_id,
                    $count_units
                );

                $found_patterns[] = Unit\UnitFoundPatterns::create(
                    $start_id / 2,
                    ($start_id + $count_units) / 2 - 1,
                    $this->getUnitTypeByPattern($pattern)
                );
            }
        }

        return $found_patterns;
    }


    /**
     * @param string $pattern
     * @return int
     */
    protected function getUnitTypeByPattern($pattern)
    {
        if (!array_key_exists($pattern, static::getConformityBetweenUnitingPatternsAndUnitType())) {
            throw new \LogicException("The conformity for the pattern " . var_export($pattern, true) . " is not declared");
        }

        return static::getConformityBetweenUnitingPatternsAndUnitType()[$pattern];
    }


    /**
     * @return int[]
     */
    public static function getConformityBetweenUnitingPatternsAndUnitType()
    {
        return [
            static::getFIO() => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD,
            static::getPhoneNumber() => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_NUMBER,
            static::getOrdinalNumber() => \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_NUMBER,
        ];
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
     * @return string
     */
    public static function getFIO()
    {
        return
            // фио в формате "Петров П. П."
            static::START_BRACE .
            UnitPseudoCodeRegistry::WORD_FIRST_LETTER_UPPERCASE .
            UnitPseudoCodeRegistry::SPACE .
            UnitPseudoCodeRegistry::LETTER_UPPERCASE .
            UnitPseudoCodeRegistry::SINGLE_DOT .
            static::START_BRACE .
            UnitPseudoCodeRegistry::SPACE .
            static::END_BRACE .
            static::EXTRA .
            UnitPseudoCodeRegistry::LETTER_UPPERCASE .
            UnitPseudoCodeRegistry::SINGLE_DOT .
            static::END_BRACE;
    }

    /**
     * @return string
     */
    public static function getPhoneNumber()
    {
        return

            // необязательный "+"
            static::START_BRACE .
            UnitPseudoCodeRegistry::PLUS .
            static::END_BRACE .
            static::EXTRA .

            // телефон в формате 7 905 123-45-67
            static::START_BRACE .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::SPACE .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::SPACE .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::DASH .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::DASH .
            UnitPseudoCodeRegistry::NUMBER .

            // или
            static::REG_OR .

            // телефон в формате 7(905)123-45-67
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::BRACE_LEFT .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::BRACE_RIGHT .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::DASH .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::DASH .
            UnitPseudoCodeRegistry::NUMBER .
            static::END_BRACE;
    }


    /**
     * @return string
     */
    public static function getOrdinalNumber()
    {
        return

            // число в формате "10-го"
            static::START_BRACE .
            UnitPseudoCodeRegistry::NUMBER .
            UnitPseudoCodeRegistry::DASH .
            UnitPseudoCodeRegistry::WORD_FIRST_LETTER_LOWERCASE .
            static::END_BRACE;
    }



}