<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 19:41
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode;


/**
 * Class UnitPseudoCodeRegistry
 */
class UnitPseudoCodeRegistry
{
    const WORD_FIRST_LETTER_UPPERCASE = 'Wu'; // слово с большой буквы
    const WORD_FIRST_LETTER_LOWERCASE = 'Wl'; // слово с маленькой буквы
    const LETTER_UPPERCASE = 'Lu'; // большая буква
    const LETTER_LOWERCASE = 'Ll'; // маленькая буква
    const SINGLE_DOT = 'Ds'; // точка
    const TRIPLE_DOT = 'Dt'; // троеточие
    const SPACE = 'Sp'; // пробел
    const NUMBER = 'Nb'; // число
    const DASH = 'Dh'; // число
    const PLUS = 'Pl'; // плюс
    const BRACE_LEFT = 'Bl'; // открывающая скобка === '('
    const BRACE_RIGHT = 'Br'; //  закрывающая скобка === ')'
    const UNKNOWN = 'Un'; // пробел


    /**
     * Получить символ псевдокода для юнита
     *
     * @param \Aot\Text\TextParserByTokenizer\Unit $unit
     * @return string
     */
    public static function getUnitCode(\Aot\Text\TextParserByTokenizer\Unit $unit)
    {
        $code = '';
        if ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD) {
            if (mb_strlen($unit, 'utf-8') === 1) {
                $code .= 'L';
            } elseif (mb_strlen($unit, 'utf-8') > 1) {
                $code .= 'W';
            }

            $regex = \Aot\Tokenizer\Token\Regex::create(\Aot\Tokenizer\Token\TokenRegexRegistry::PATTERN_UPPERCASE);
            $regex->addStartingCaret();
            if ($regex->match($unit)) {
                $code .= 'u';
            } else {
                $code .= 'l';
            }
        } elseif ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_PUNCTUATION) {
            if (mb_strlen($unit, 'utf-8') === 1) {
                if ((string)$unit === '.') {
                    $code = 'Ds';
                } elseif ((string)$unit === '(') {
                    $code = 'Bl';
                } elseif ((string)$unit === ')') {
                    $code = 'Br';
                }
            }
            else{
                $code = 'Un';
            }
        } elseif ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE) {
            $code = 'Sp';
        } elseif ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_NUMBER) {
            $code = 'Nb';
        } elseif ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_DASH) {
            $code = 'Dh';
        } elseif ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_OTHER) {
            if (mb_strlen($unit, 'utf-8') === 1) {
                if ((string)$unit === '+') {
                    $code = 'Pl';
                }
            }
            else{
                $code = 'Un';
            }
        }
        else {
            $code = 'Un';
        }
        return $code;
    }

}
