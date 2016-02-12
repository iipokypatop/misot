<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 17:44
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode;


use Aot\Text\TextParserByTokenizer\PseudoCode;

class PseudoCodeDriver
{

    /**
     * @return \Aot\Text\TextParserByTokenizer\PseudoCodeDriver
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\Token\TokenFoundPatterns[]
     */
    public function findBorderGroupsOfUnits(array $units)
    {
        foreach ($units as $unit) {
            assert(is_a($unit, \Aot\Text\TextParserByTokenizer\Unit::class, true));
        }

        // создание псевдокода по токенам
        $pseudo_code = $this->createUnitsPseudoCode($units);

        // поиск шаблонов в псевдокоде
        $uniting_patterns = PseudoCode\Unit\UnitUnitingPatterns::create();
        return $uniting_patterns->findEntryPatterns($pseudo_code);
    }


    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\Token\TokenFoundPatterns[]
     */
    public function findBorderGroupsOfTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        // создание псевдокода по токенам
        $pseudo_code = $this->createTokensPseudoCode($tokens);

        // поиск шаблонов в псевдокоде
        $uniting_patterns = PseudoCode\Token\TokenUnitingPatterns::create();
        return $uniting_patterns->findEntryPatterns($pseudo_code);
    }

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return string
     */
    protected function createTokensPseudoCode(array $tokens)
    {
        $pseudo_code_array = [];

        foreach ($tokens as $token) {
            $pseudo_code_array[] = PseudoCode\Token\TokenPseudoCodeRegistry::getTokenCode($token->getType());
        }
        return join('', $pseudo_code_array);
    }

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @return string
     */
    protected function createUnitsPseudoCode(array $units)
    {

        $pseudo_code_array = [];
        foreach ($units as $unit) {
            $pseudo_code_array[] = PseudoCode\Unit\UnitPseudoCodeRegistry::getUnitCode($unit);
        }
        return join('', $pseudo_code_array);
    }
}