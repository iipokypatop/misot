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
     * @param \SplDoublyLinkedList $tokens_queue
     * @return \Aot\Text\TextParserByTokenizer\Unit
     */
    public static function create(\SplDoublyLinkedList $tokens_queue)
    {
        foreach ($tokens_queue as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        $ob = new static();

        $ob->search($tokens_queue);

        return $ob;
    }

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return \Aot\Text\TextParserByTokenizer\Unit
     */
    public static function createWithTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        if ([] === $tokens) {
            throw new \LogicException('Failed to create the Unit, input array is empty!');
        }
        $ob = new static();

        $ob->tokens = $tokens;

        // тип наследуется от первого токена
        $ob->type = $tokens[0]->getType();

        return $ob;
    }


    protected function __construct()
    {
    }


    /**
     * @param \SplDoublyLinkedList $tokens_queue
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function search(\SplDoublyLinkedList $tokens_queue)
    {
        foreach ($tokens_queue as $id => $token) {
            $this->tokens[] = $token;
            $this->type = $token->getType();
            unset($tokens_queue[$id]);
            break;
        }
    }

    /**
     * @return \Aot\Tokenizer\Token\Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }


    public function __toString()
    {
        return join('', $this->tokens);
    }
}