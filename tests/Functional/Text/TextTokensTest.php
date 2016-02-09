<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 19:44
 */

namespace AotTest\Functional\Text;


use Aot\Tokenizer\Token\Token;
use MivarTest\PHPUnitHelper;

class TextTokensTest extends \AotTest\AotDataStorage
{

    /**
     * Без фильтра и обычный текст
     */
    public function testSearchUnitsWithoutFilterAndNormalText()
    {
        $text = 'человек кого-то увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::create($text);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'splitTextIntoTokens', []);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$tokens]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$this->getTokens()]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        $groups_tokens = PHPUnitHelper::callProtectedMethod($parser, 'groupingOfTokens', [$tokens, $found_patterns]);
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $groups_tokens]);

        // запускаем
        $this->assertEquals('человек кого-то увидел, или нет...', join('', $units));
        $this->assertEquals(11, count($units));
    }


    /**
     * Без фильтра и текст с кривыми символами
     */
    public function testSearchUnitsWithoutFilterAndBadText()
    {
        $text = 'чело£век кого-то ¶увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::create($text);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'splitTextIntoTokens', []);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$tokens]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$this->getTokens()]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        $groups_tokens = PHPUnitHelper::callProtectedMethod($parser, 'groupingOfTokens', [$tokens, $found_patterns]);
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $groups_tokens]);

        // запускаем
        $this->assertEquals('чело£век кого-то ¶увидел, или нет...', join('', $units));
        $this->assertEquals(14, count($units));
    }

    public function testSearchUnitsWithFilter()
    {
        $text = 'чело£век кого-то ¶увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::create(
            $text,
            [
                \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::FILTER_NO_VALID_SYMBOLS
            ]
        );

        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$this->getTokens()]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$tokens]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        $groups_tokens = PHPUnitHelper::callProtectedMethod($parser, 'groupingOfTokens', [$tokens, $found_patterns]);
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $groups_tokens]);
        $this->assertEquals('человек кого-то увидел, или нет...', join('', $units));
        $this->assertEquals(11, count($units));
    }

    public function testSearchUnitsWithTokenizerWithFilter()
    {
        $text = 'чело£век    кого-то ¶увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::create(
            $text,
            [
                \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::FILTER_NO_VALID_SYMBOLS
            ]
        );
        // запускаем
        $parser->run();
        $this->assertEquals('человек    кого-то увидел, или нет...', join('', $parser->getUnits()));
        $this->assertEquals(11, count($parser->getUnits()));
    }

    public function testSearchPatterns()
    {
        /**
         * шаблоны:
         * 1) WDWDW
         * 2) SS
         * 3) PPP
         * 4) WWW
         * 5) WW
         * 6) WDW
         */
        $pseudo_code = 'WDWDWSSWPSPPPSWWWPWWDW';
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found = $uniting_patterns->findEntryPatterns($pseudo_code);

        $this->assertEquals(
            [
                0 => [
                    'start_id' => 0,
                    'end_id' => 4,
                ],
                1 => [
                    'start_id' => 19,
                    'end_id' => 21,
                ],
                2 => [
                    'start_id' => 10,
                    'end_id' => 12,
                ],
                3 => [
                    'start_id' => 14,
                    'end_id' => 16,
                ],
                4 => [
                    'start_id' => 18,
                    'end_id' => 19,
                ],
                5 => [
                    'start_id' => 5,
                    'end_id' => 6,
                ],
            ], $found);
    }

    protected function getTokens()
    {
        // 'человек кого-то увидел, или нет...';
        $tokens = [];
        $tokens[] = Token::create('чело£век', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('кого', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create('-', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH);
        $tokens[] = Token::create('то', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('¶увидел', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(',', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokens[] = Token::create(' ', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('или', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('нет', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokens[] = Token::create('.', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokens[] = Token::create('.', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokens[] = Token::create('.', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);

        return $tokens;
    }
}