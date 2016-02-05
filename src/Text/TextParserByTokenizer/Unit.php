<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 05/02/16
 * Time: 15:49
 */

namespace Aot\Text\TextParserByTokenizer;


class Unit
{
    const UNIT_TYPE_WORD = 1;
    const UNIT_TYPE_PUNCTUATION = 2;
    const UNIT_TYPE_SPACE = 3;
    const UNIT_TYPE_DASH = 4;
    const UNIT_TYPE_NUMBER = 5;
    const UNIT_TYPE_OTHER = 6;

    /** @var \Aot\Tokenizer\Token\Token[] */
    protected $tokens;

    /** @var int */
    protected $type;

    /**
     * @param \SplDoublyLinkedList $tokens
     * @return \Aot\Text\TextParserByTokenizer\Unit
     */
    public static function create(\SplDoublyLinkedList $tokens)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        $ob = new static();

        /*$tokens = */$ob->search($tokens);

        return $ob;
    }


    protected function __construct()
    {
    }


    /**
     * @param \SplDoublyLinkedList $tokens
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function search(\SplDoublyLinkedList $tokens)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        foreach ($tokens as $id => $token) {
            $this->tokens[] = $token;
            $this->type = $token->getType();
            unset($tokens[$id]);
//            return $tokens;
        }
    }

    /**
     * @return \Aot\Tokenizer\Token\Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}