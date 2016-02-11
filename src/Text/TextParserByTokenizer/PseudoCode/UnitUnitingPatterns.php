<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 08/02/16
 * Time: 13:00
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode;

/**
 * Шаблоны объединения Unit'ов представленных в виде псевдокода
 * Class UnitingPatterns
 */
class UnitUnitingPatterns
{
    const FIO1 = 'WuSpLuDs(Sp)?LuDs'; // фио в формате Петров П. П.
    const FIO2 = 'WuSpWuSpWu'; // фио в формате Петров Петр Петрович
    const PHONE_NUMBER1 = '(Pl)?NbSpNbSpNbDhNbDhNb'; // телефон в формате +7 905 123-45-67
    const PHONE_NUMBER2 = '(Pl)?NbBlNbBrNbDhNbDhNb'; // телефон в формате +7(905)123-45-67


    const DELIMITER = '/';
    const MODIFIERS = 'u';
    const REPLACED_SYMBOL = '0';


    /**
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\UnitUnitingPatterns
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
            static::FIO1,
            static::FIO2,
            static::PHONE_NUMBER1,
            static::PHONE_NUMBER2,
        ];
    }


    /**
     * @param string $pseudo_code
     * @return  \Aot\Text\TextParserByTokenizer\PseudoCode\TokenFoundPatterns[]
     */
    public function findEntryPatterns($pseudo_code)
    {
        assert(is_string($pseudo_code));
        $found_patterns = [];
        foreach (static::getUnitingPatterns() as $pattern) {

            $matches_all = $this->getMatchesByPattern($pattern, $pseudo_code);

            foreach ($matches_all as $matches) {

                $count_units = mb_strlen($matches[0], 'utf-8');
                $start_id = $matches[1];

                $pseudo_code = substr_replace(
                    $pseudo_code,
                    str_repeat(static::REPLACED_SYMBOL, $count_units),
                    $start_id,
                    $count_units
                );

                $found_patterns[] = [
                    $start_id / 2,
                    ($start_id + $count_units) / 2 - 1,
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


}