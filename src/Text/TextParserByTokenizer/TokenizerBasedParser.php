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

    /** @var \Aot\Text\TextParserByTokenizer\PseudoCodeDriver */
    protected $pseudo_code_driver;

    /** @var  \Aot\Text\TextParserByTokenizer\Sentence[] */
    protected $sentences;

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
        $ob = static::create();
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

        $this->sentences = $this->findSentences();
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

                if (empty($tokens[$i])) {
                    throw new \LogicException('Token with id = ' . $i . ' does not exists');
                }

                $groups_tokens[] = $tokens[$i];
                unset($tokens[$i]);
            }
            $units[$start] = \Aot\Text\TextParserByTokenizer\Unit::create($groups_tokens, $found_pattern->getType());
        }

        // прогоняем оставшиеся токены
        foreach ($tokens as $id => $token) {
            $units[$id] = \Aot\Text\TextParserByTokenizer\Unit::create(
                [$token],
                \Aot\Text\TextParserByTokenizer\TokenAndUnitRegistry::getAssociatedUnitTypeAndTokenTypeMap()[$token->getType()]
            );
        }

        ksort($units);
        return array_values($units);
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Tokenizer
     */
    public function getTokenizer()
    {
        return $this->tokenizer;
    }

    /**
     * Поиск предложений
     * @return \Aot\Text\TextParserByTokenizer\Unit[][]
     */
    protected function findSentences()
    {
        $sentences = [];
        $start_id = -1;
        foreach ($this->units as $id => $unit) {
            if ($this->isSymbolOfTheEndOfSentence($id) || $this->isEndOfText($id)) {
                $sentence_units = [];
                for ($i = $start_id + 1; $i <= $id; $i++) {
                    $sentence_units[] = $this->units[$i];
                }

                $start_id = $id;
                $sentences[] = \Aot\Text\TextParserByTokenizer\Sentence::create($sentence_units, count($sentences));
            }
        }

        return $sentences;
    }

    /**
     * @param $id
     * @return bool
     */
    protected function isSymbolOfTheEndOfSentence($id)
    {
        return (
            ((string)$this->units[$id] === '.'
                || (string)$this->units[$id] === '...'
                || (string)$this->units[$id] === '!'
                || (string)$this->units[$id] === '?'
            )
            && $this->isEndOfSentence($id)
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function isEndOfSentence($id)
    {
        return ($this->isSpace($id + 1) && $this->isCapitalizedWord($id + 2));
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function isEndOfText($id)
    {
        return (!isset($this->units[$id + 1]));
    }


    /**
     * Проверка на пробел
     * @param $id
     * @return bool
     */
    protected function isSpace($id)
    {
        return (
            isset($this->units[$id])
            && $this->units[$id]->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE
        );
    }

    /**
     * Проверка на слово, начинающееся с большой буквы
     * @param $id
     * @return bool
     */
    protected function isCapitalizedWord($id)
    {
        if (!isset($this->units[$id])
            || $this->units[$id]->getType() !== \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_WORD
        ) {
            return false;
        }
        $text = $this->units[$id]->getTokens()[0]->getText();


        $regex = \Aot\Tokenizer\Token\Regex::create(\Aot\Tokenizer\Token\TokenRegexRegistry::PATTERN_UPPERCASE);
        return $regex->match($text);
    }

    /**
     * @return \Aot\Text\TextParserByTokenizer\Sentence[]
     */
    public function getSentences()
    {
        return $this->sentences;
    }

}