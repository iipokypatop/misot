<?php

namespace Aot\Text\TextParserByTokenizer;

/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 05/02/16
 * Time: 15:10
 */
class TokenizerBasedParser
{
    /** @var string */
    protected $text;

    /** @var  \Aot\Text\TextParser\Filters\Base[] */
    protected $filters = [];

    /** @var  \Aot\Text\TextParserByTokenizer\Unit[] */
    protected $units;

    /** @var  \Aot\Text\TextParserByTokenizer\Tokenizer */
    protected $tokenizer;

    /** @var \Aot\Text\TextParserByTokenizer\PseudoCodeDriver  */
    protected $pseudo_code_driver;

    /**
     * @return \Aot\Text\TextParserByTokenizer\TokenizerBasedParser
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\TokenizerBasedParser
     */
    public static function createDefaultConfig()
    {
        $ob = new static();
        // TODO: почему не видит методы?
        $ob->tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $ob->tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $ob->tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $ob->tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH);
        $ob->tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        return $ob;
    }

    protected function __construct()
    {
        $this->tokenizer = \Aot\Text\TextParserByTokenizer\Tokenizer::createEmptyConfiguration();
        $this->pseudo_code_driver = \Aot\Text\TextParserByTokenizer\PseudoCodeDriver::create();
    }

    /**
     * @param \Aot\Text\TextParser\Filters\Base $filter
     */
    public function addFilter(\Aot\Text\TextParser\Filters\Base $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @param string $text
     */
    public function run($text)
    {
        assert(is_string($text));
        $this->text = $text;

        // разбиение текста на токены
        $tokens = $this->splitTextIntoTokens($text);

        // фильтрация токенов
        $tokens = $this->filterTokens($tokens);

        // создание юнитов
        $this->units = $this->createUnits($tokens);
    }

    /**
     * Разбить текст на токены
     * @param string $text
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function splitTextIntoTokens($text)
    {
        $this->tokenizer->tokenize($text);
        return $this->tokenizer->getTokens();
    }

    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function filterTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            assert(is_a($token, \Aot\Tokenizer\Token\Token::class, true));
        }

        if (count($this->filters) === 0) {
            return $tokens;
        }

        /** @var \Aot\Tokenizer\Token\Token[] $filtered_tokens */
        $filtered_tokens = [];

        foreach ($tokens as $token) {
            foreach ($this->filters as $filter) {
                $filtered_text = $filter->filter($token->getText());
                if ($filtered_text === '') {
                    continue;
                }
                $filtered_tokens[] = \Aot\Tokenizer\Token\Token::create($filtered_text, $token->getType());
            }
        }

        return $filtered_tokens;
    }


    /**
     * Создание Unit'ов
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    protected function createUnits(array $tokens)
    {
        $units = [];

        $border_groups_of_tokens = $this->pseudo_code_driver->findBorderGroupsOfTokens($tokens);

        foreach ($border_groups_of_tokens as $found_pattern) {
            $start = $found_pattern->getStart();
            $end = $found_pattern->getEnd();
            $groups_tokens = [];
            for ($i = $start; $i <= $end; $i++) {
                $groups_tokens[] = $tokens[$i];
                unset($tokens[$i]);
            }
            $units[$start] = \Aot\Text\TextParserByTokenizer\Unit::createWithTokens($groups_tokens, $found_pattern->getType());
        }

        // прогоняем оставшиеся токены
        foreach ($tokens as $id => $token) {
            $units[$id] = \Aot\Text\TextParserByTokenizer\Unit::createWithTokens([$token], $token->getType());
        }

        ksort($units);
        return $units;


    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }


}