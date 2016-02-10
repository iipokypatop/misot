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


    public function testSymbolsMap()
    {
        $text = 'Человек пошел   в лес. Он потерялся.';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser->run($text);


        $map = $parser->getSymbolsMap();
        $this->assertEquals(2, count($map));
        $checking = [];
        foreach ($map as $sentence) {
            foreach ($sentence as $unit) {
                $checking = array_merge($checking, $unit);
            }
        }
        $symbols_from_text = preg_split('//u', $text, 0, PREG_SPLIT_NO_EMPTY);
        $this->assertEquals($symbols_from_text, $checking);
    }

    /**
     * Без фильтра и сложных юнитов
     */
    public function testSplitSimpleTextInSentences1()
    {
        $text = 'человек кого-то   увидел, или нет... Показалось наверное.  Блаблабла.  ';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser->run($text);

        // запускаем
        $this->assertEquals($text, join('', $parser->getUnits()));
        $this->assertEquals(3, count($parser->getSentences()));
        $rebuild_text = '';
        foreach ($parser->getSentences() as $sentence) {
            $rebuild_text .= (string)$sentence;
        }
        $this->assertEquals($text, $rebuild_text);
        $this->assertEquals(20, count($parser->getUnits()));
    }


    /**
     * Без фильтра и сложных юнитов
     */
    public function testSplitSimpleTextInSentences2()
    {
        $text = 'человек кого-то   увидел, или нет... Показалось наверное.  Блаблабла   ';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser->run($text);

        // запускаем
        $this->assertEquals($text, join('', $parser->getUnits()));
        $this->assertEquals(3, count($parser->getSentences()));
        $rebuild_text = '';
        foreach ($parser->getSentences() as $sentence) {
            $rebuild_text .= (string)$sentence;
        }
        $this->assertEquals($text, $rebuild_text);
        $this->assertEquals(19, count($parser->getUnits()));
    }


    public function testSplitSimpleTextInSentences3()
    {
        $text = 'Человек пошел в лес. Он потерялся.';
        $parser = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser->run($text);

        // запускаем
        $this->assertEquals('Человек пошел в лес. Он потерялся.', join('', $parser->getUnits()));
        $this->assertEquals(2, count($parser->getSentences()));
        $rebuild_text = '';
        foreach ($parser->getSentences() as $sentence) {
            $rebuild_text .= (string)$sentence;
        }
        $this->assertEquals($text, $rebuild_text);
        $this->assertEquals(13, count($parser->getUnits()));
    }


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
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens]);

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
        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens]);

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

        $units = PHPUnitHelper::callProtectedMethod($parser, 'createUnits', [$tokens]);
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
//        $this->markTestSkipped('Сломался!');
        /**
         * шаблоны:
         * 1) WDWDW
         * 2) SS
         * 3) PPP
         * 4) WWW
         * 5) WDW
         */
        $pseudo_code = 'WDWDWSSWPSPPPSWWWPWWDW';
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\PseudoCode\TokenUnitingPatterns::create();
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
                4 => [5, 6],
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