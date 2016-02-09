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
     * Без фильтра и сложных юнитов
     */
    public function testSearchUnitsWithoutComplexUnits()
    {
        $text = 'человек пошел в лес';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser->run($text);

        // запускаем
        $this->assertEquals('человек пошел в лес', join('', $parser->getUnits()));
        $this->assertEquals(7, count($parser->getUnits()));
    }


    /**
     * Без фильтра и обычный текст
     */
    public function testSearchUnitsWithoutFilterAndNormalText()
    {
        $text = 'человек кого-то увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'splitTextIntoTokens', [$text]);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$tokens]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$this->getTokens()]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $found_patterns]);

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
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'splitTextIntoTokens', [$text]);
        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$tokens]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$this->getTokens()]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $found_patterns]);

        // запускаем
        $this->assertEquals('чело£век кого-то ¶увидел, или нет...', join('', $units));
        $this->assertEquals(14, count($units));
    }

    public function testSearchUnitsWithFilter()
    {
        $text = 'чело£век кого-то ¶увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();

        $logger = \Aot\Text\TextParser\Logger::create();
        $parser->addFilter(\Aot\Text\TextParser\Filters\NoValid::create($logger));

        $tokens = PHPUnitHelper::callProtectedMethod($parser, 'filterTokens', [$this->getTokens()]);
        $pseudo_code = PHPUnitHelper::callProtectedMethod($parser, 'createPseudoCode', [$tokens]);
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens, $found_patterns]);
        $this->assertEquals('человек кого-то увидел, или нет...', join('', $units));
        $this->assertEquals(11, count($units));
    }

    public function testSearchUnitsWithTokenizerWithFilter()
    {
        $text = 'чело£век кого-то   ¶увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();

        $logger = \Aot\Text\TextParser\Logger::create();
        $parser->addFilter(\Aot\Text\TextParser\Filters\NoValid::create($logger));

        // запускаем
        $parser->run($text);
        $this->assertEquals('человек кого-то   увидел, или нет...', join('', $parser->getUnits()));
        $this->assertEquals(11, count($parser->getUnits()));
    }

    public function testSearchPatterns()
    {
        $this->markTestSkipped('Сломался!');
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

        $found_array = [];
        foreach ($found as $item) {
            $found_array[] = [
                $item->getStart(),
                $item->getEnd(),
            ];
        }
        $this->assertEquals(
            [
                0 => [0, 4],
                1 => [19, 21],
                2 => [10, 12],
                3 => [14, 16],
                4 => [18, 19],
                5 => [5, 6],
            ],
            $found_array
        );
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