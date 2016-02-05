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
        if (isset($this->filters)) {
            $this->filterTokens($this->tokens);
        }

        $this->createUnits();

    }

    /**
     * Разбить текст на токены
     */
    protected function splitTextIntoTokens()
    {
        #TODO
        return;
    }


    /**
     * @param \Aot\Tokenizer\Token\Token[] $tokens
     */
    protected function filterTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            foreach ($this->filters as $filter) {
                $filtered_text = $filter->filter($token->getText());
                $token->setText($filtered_text);
            }
        }
    }

    /**
     * Создание Unit'ов
     */
    protected function createUnits()
    {
        $units = [];
        $tokens_queue = new \SplQueue();
        foreach ($this->tokens as $token) {
            $tokens_queue->push($token);
        }

        while ($tokens_queue->count() > 0) {
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
}