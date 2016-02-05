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

    public function testLaunch()
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

        die('test');
    }

    public function testLaunch2()
    {
        $tokens = $this->getTokens();

//        var_dump($tokens);
        $this->filterTokens($tokens);
//        var_dump($doubly_list);

//        $doubly_list = new \SplDoublyLinkedList();
//        foreach ($tokens as $token) {
//            $doubly_list->push($token);
//        }

        die('test');
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
        $tokens[] = Token::create('чело£век', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', Token::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('кого', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create('-', Token::TOKEN_TYPE_DASH);
        $tokens[] = Token::create('то', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', Token::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('¶увидел', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(',', Token::TOKEN_TYPE_PUNCTUATION);
        $tokens[] = Token::create(' ', Token::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('или', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create(' ', Token::TOKEN_TYPE_SPACE);
        $tokens[] = Token::create('нет', Token::TOKEN_TYPE_WORD);
        $tokens[] = Token::create('...', Token::TOKEN_TYPE_PUNCTUATION);
        return $tokens;
    }
}