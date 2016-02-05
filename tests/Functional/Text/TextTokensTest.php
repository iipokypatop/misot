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
    use DataProvidersParseText;

    public function testSearchUnits()
    {
        $text = 'человек кого-то увидел, или нет...';
        $parser = \Aot\Text\TextParserByTokenizer\Parser::create(
            $text,
            [
                \Aot\Text\TextParserByTokenizer\Parser::FILTER_NO_VALID_SYMBOLS
            ]
        );

        // устанавливаем собранные руками токены
        PHPUnitHelper::setProtectedProperty($parser, 'tokens', $this->getTokens());

        // запускаем
        $parser->run();

        // токены соответсвуют юнитам
        foreach ($parser->getUnits() as $id => $unit) {
            $this->assertEquals($parser->getTokens()[$id], $unit->getTokens()[0]);
        }
    }

    public function testLaunchTokenizer()
    {

        $tokenizer = \Aot\Text\TextParserByTokenizer\ParseTokenizer::createEmptyConfiguration();
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH);

        $tokens = $tokenizer->tokenize('человек кого-то увидел, или нет...');
    }



    public function testTokensAndFilter()
    {
        $tokens = $this->getTokens();

        $this->filterTokens($tokens);
    }

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     */
    protected function filterTokens(array $tokens)
    {

        $logger = \Aot\Text\TextParser\Logger::create();
        $filterNoValid = \Aot\Text\TextParser\Filters\NoValid::create($logger);

        foreach ($tokens as $token) {
            $filtered_text = $filterNoValid->filter($token->getText());
            $token->setText($filtered_text);
        }
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
        $tokens[] = Token::create('...', \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);

        return $tokens;
    }
}