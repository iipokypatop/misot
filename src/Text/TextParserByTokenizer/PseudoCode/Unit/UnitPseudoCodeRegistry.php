<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 19:41
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode\Unit;
use Aot\Text\Encodings;


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
    const DASH = 'Dh'; // тире
    const PLUS = 'Pl'; // плюс
    const BRACE_LEFT = 'Bl'; // открывающая скобка === '('
    const BRACE_RIGHT = 'Br'; //  закрывающая скобка === ')'
    const UNKNOWN = 'Un'; // неизвестно


    /**
     * Получить символ псевдокода для юнита
     *
     * @param \Aot\Text\TextParserByTokenizer\Unit $unit
     * @return string
     */
    public static function getUnitCode(\Aot\Text\TextParserByTokenizer\Unit $unit)
    {
        $code = '';
        $unit_string_representation = $unit->getStringRepresentation(false);
        $unit_type = $unit->getType();
        $unit_length = mb_strlen($unit_string_representation, Encodings::UTF_8);
        if ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD) {
            $regex = \Aot\Tokenizer\Token\Regex::create(\Aot\Tokenizer\Token\TokenRegexRegistry::PATTERN_UPPERCASE);
            $regex->addStartingCaret();
            $uppercase = $regex->match($unit_string_representation);
            if ($unit_length === 1) {
                if ($uppercase) {
                    $code = static::LETTER_UPPERCASE;
                } else {
                    $code = static::LETTER_LOWERCASE;
                }
            } elseif ($unit_length > 1) {
                if ($uppercase) {
                    $code = static::WORD_FIRST_LETTER_UPPERCASE;
                } else {
                    $code = static::WORD_FIRST_LETTER_LOWERCASE;
                }
            }
        } elseif ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_PUNCTUATION) {
            if ($unit_length === 1) {
                if ($unit_string_representation === '.') {
                    $code = static::SINGLE_DOT;
                } elseif ($unit_string_representation === '(') {
                    $code = static::BRACE_LEFT;
                } elseif ($unit_string_representation === ')') {
                    $code = static::BRACE_RIGHT;
                }
            }
        } elseif ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE) {
            $code = static::SPACE;
        } elseif ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_NUMBER) {
            $code = static::NUMBER;
        } elseif ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_DASH) {
            $code = static::DASH;
        } elseif ($unit_type === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_OTHER) {
            if (mb_strlen($unit_string_representation, Encodings::UTF_8) === 1) {
                if ($unit_string_representation === '+') {
                    $code = static::PLUS;
                }
            }
        }

        if ($code === '') {
            return static::UNKNOWN;
        }

        return $code;
    }

}
