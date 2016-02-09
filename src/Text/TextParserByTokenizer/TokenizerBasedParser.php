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

    // фильтрация невалидных символов
    const FILTER_NO_VALID_SYMBOLS = 1;


    /**
     * @param string $text
     * @param int[] $filter_flags
     * @return \Aot\Text\TextParserByTokenizer\TokenizerBasedParser
     */
    public static function create($text, array $filter_flags = [])
    {
        return new static($text, $filter_flags);
    }


    /**
     * @param string $text
     * @param \Aot\Text\TextParser\Filters\Base[] $filter_flags
     */
    protected function __construct($text, array $filter_flags)
    {
        assert(is_string($text));
        $this->text = $text;

        if ([] === $filter_flags) {
            return;
        }

        $logger = \Aot\Text\TextParser\Logger::create();
        foreach ($filter_flags as $filter_flag) {
            if (self::FILTER_NO_VALID_SYMBOLS === $filter_flag) {
                $this->filters[] = \Aot\Text\TextParser\Filters\NoValid::create($logger);
            }
        }
    }

    public function run()
    {
        // разбиение текста на токены
        $tokens = $this->splitTextIntoTokens();

        // фильтрация токенов
        $tokens = $this->filterTokens($tokens);

        // создание псевдокода по токенам
        $pseudo_code = $this->createPseudoCode($tokens);

        // поиск шаблонов в псевдокоде
        $uniting_patterns = \Aot\Text\TextParserByTokenizer\UnitingPatterns::create();
        $found_patterns = $uniting_patterns->findEntryPatterns($pseudo_code);

        // объединение токенов в группы по найденным шаблонам
        $groups_tokens = $this->groupingOfTokens($tokens, $found_patterns);

        // создание юнитов
        $this->units = $this->createUnits($tokens, $groups_tokens);
    }

    /**
     * Разбить текст на токены
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function splitTextIntoTokens()
    {
        $tokenizer = \Aot\Text\TextParserByTokenizer\Tokenizer::createEmptyConfiguration();
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokenizer->tokenize($this->text);
        return $tokenizer->getTokens();
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
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return string
     */
    protected function createPseudoCode(array $tokens)
    {
        $pseudo_code_array = [];

        // токены соответсвуют юнитам
        foreach ($tokens as $id => $token) {
            $pseudo_code_array[$id] = \Aot\Text\TextParserByTokenizer\PseudoCodeRegistry::getTokenCode($token->getType());
        }
        return join('', $pseudo_code_array);
    }

    /**
     * Создание Unit'ов
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @param \Aot\Tokenizer\Token\Token[][] $groups_tokens
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    protected function createUnits(array $tokens, array $groups_tokens)
    {
        $amount = count($tokens);
        $units = [];
        for ($i = 0; $i < $amount; $i++) {
            if (!empty($groups_tokens[$i])) {
                $units[] = \Aot\Text\TextParserByTokenizer\Unit::createWithTokens($groups_tokens[$i]);
                $i += count($groups_tokens[$i]) - 1;
            } else {
                $units[] = \Aot\Text\TextParserByTokenizer\Unit::createWithTokens([$tokens[$i]]);
            }
        }
        return $units;
    }

    /**
     * @param array $tokens
     * @param array $found_patterns
     * @return array
     */
    protected function groupingOfTokens(array $tokens, array $found_patterns)
    {
        $groups_tokens = [];
        foreach ($found_patterns as $found_pattern) {
            $start = $found_pattern['start_id'];
            $end = $found_pattern['end_id'];
            for ($i = $start; $i <= $end; $i++) {
                $groups_tokens[$start][] = $tokens[$i];
            }
        }
        return $groups_tokens;
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

}