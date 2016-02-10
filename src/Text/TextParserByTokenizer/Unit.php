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
    protected $tokens = [];

    /** @var int */
    protected $type;

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @param int $type
     * @return Unit
     */
    public static function create(array $tokens, $type)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }
        assert(is_int($type));

        if (!in_array($type, \Aot\Text\TextParserByTokenizer\TokenAndUnitRegistry::getAssociatedUnitTypeAndTokenTypeMap())) {
            throw new \LogicException('The type of token ' . var_export($type, true) . ' does not associated to any unit token');
        }

        if ([] === $tokens) {
            throw new \LogicException('Failed to create the Unit, input array is empty!');
        }
        $ob = new static();
        $ob->tokens = $tokens;
        $ob->type = $type;

        return $ob;
    }


    protected function __construct()
    {
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

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}