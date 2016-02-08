<?php

namespace Aot\Text\TextParserByTokenizer;

/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 05/02/16
 * Time: 15:10
 */
class Parser
{
    /** @var string */
    protected $text;

    /** @var  \Aot\Text\TextParser\Filters\Base[] */
    protected $filters;

    /** @var \Aot\Tokenizer\Token\Token[] */
    protected $tokens;

    /** @var \Aot\Tokenizer\Token\Token[] */
    protected $filtered_tokens;

    /** @var  \Aot\Text\TextParserByTokenizer\Unit[] */
    protected $units;

    // фильтрация невалидных символов
    const FILTER_NO_VALID_SYMBOLS = 1;


    /**
     * @param string $text
     * @param int[] $filter_flags
     * @return \Aot\Text\TextParserByTokenizer\Parser
     */
    public static function create($text, array $filter_flags)
    {
        return new static($text, $filter_flags);
    }

    protected function __construct($text, array $filter_flags)
    {
        assert(is_string($text));
        $this->text = $text;

        if (empty($filter_flags)) {
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
        $this->splitTextIntoTokens();
        $tokens = $this->tokens;
        if (isset($this->filters)) {
            $tokens = $this->filterTokens($this->tokens);
            $this->filtered_tokens = $tokens;
        }


        $pseudo_code = $this->createPseudoCode($tokens);

        $this->findEntryPatterns($pseudo_code);

        die();
        print_r($this->tokens);
        print_r($this->filtered_tokens);
        $this->createUnits($tokens);

        $this->combineUnites();

    }

    /**
     * Разбить текст на токены
     */
    protected function splitTextIntoTokens()
    {
        return;
        $tokenizer = \Aot\Text\TextParserByTokenizer\Tokenizer::createEmptyConfiguration();
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_NUMBER);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_SPACE);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION);
        $tokenizer->addTokenType(\Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_DASH);
        $tokenizer->tokenize($this->text);
        $this->tokens = $tokenizer->getTokens();
    }


    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     * @return \Aot\Tokenizer\Token\Token[]
     */
    protected function filterTokens(array $tokens)
    {
        /** @var \Aot\Tokenizer\Token\Token[] $filtered_tokens */
        $filtered_tokens = [];

        foreach ($tokens as $token) {
            foreach ($this->filters as $filter) {
                $filtered_text = $filter->filter($token->getText());
                $filtered_tokens[] = \Aot\Tokenizer\Token\Token::create($filtered_text, $token->getType());
            }
        }

        return $filtered_tokens;
    }

    /**
     * Создание Unit'ов
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     */
    protected function createUnits(array $tokens)
    {
        $units = [];
        $tokens_queue = new \SplQueue();
        foreach ($tokens as $token) {
            $tokens_queue->push($token);
        }

        $max_iterations = $tokens_queue->count();
        $iteration = 0;

        while ($tokens_queue->count() > 0 && $iteration++ < $max_iterations) {
            $units[] = \Aot\Text\TextParserByTokenizer\Unit::create($tokens_queue);
        }
        $this->units = $units;
    }


    /**
     * @return \Aot\Tokenizer\Token\Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @return \Aot\Tokenizer\Token\Token[]
     */
    public function getFilteredTokens()
    {
        return $this->filtered_tokens;
    }

    protected function combineUnites()
    {
        $code = $this->createPseudoCode();
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
     * @param string $pseudo_code
     */
    protected function findEntryPatterns($pseudo_code){
        foreach (\Aot\Text\TextParserByTokenizer\UnitingPatterns::getUnitingPatterns() as $rule) {
            if (preg_match_all('/' . $rule . '/', $pseudo_code, $matches_all, PREG_OFFSET_CAPTURE)) {
                foreach ($matches_all[0] as $matches) {

                    $count_units = strlen($matches[0]);
                    $start_id = $matches[1];

                    $united_tokens[] = [
                        'start_id' => $start_id,
                        'end_id' => $start_id + $count_units - 1,
                    ];
                }
            }
        }
        print_r($united_tokens);
    }

}