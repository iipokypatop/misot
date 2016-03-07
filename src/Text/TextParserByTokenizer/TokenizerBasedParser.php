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
    const SPLIT_REGEX = '//u';

    /** @var string */
    protected $text;

    /** @var  \Aot\Text\TextParser\Filters\Base[] */
    protected $filters = [];

    /** @var  \Aot\Text\TextParserByTokenizer\Unit[] */
    protected $units = [];

    /** @var  \Aot\Text\TextParserByTokenizer\Tokenizer */
    protected $tokenizer;

    /** @var \Aot\Text\TextParserByTokenizer\PseudoCode\PseudoCodeDriver */
    protected $pseudo_code_driver;

    /** @var  \Aot\Text\TextParserByTokenizer\Sentence[] */
    protected $sentences = [];

    /** @var  string[][][] */
    protected $symbols_map = [];

    protected $symbols_map_enabled = false;

    protected function __construct()
    {
        $this->tokenizer = \Aot\Text\TextParserByTokenizer\Tokenizer::createEmptyConfiguration();
        $this->pseudo_code_driver = PseudoCode\PseudoCodeDriver::create();
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

    /**
     * @return \Aot\Text\TextParserByTokenizer\TokenizerBasedParser
     */
    public static function create()
    {
        return new static();
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
        $units = $this->createUnits($tokens);

        // объединение юнитов
        $this->units = $this->uniteUnits($units);

        // группировка Unit'ов по предложениям
        $this->sentences = $this->findSentences();

        if ($this->symbols_map_enabled) {
            $this->symbols_map = $this->createSymbolsMap();
        }
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
                    throw new \LogicException('Token with id = ' . var_export($i, true) . ' does not exists');
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
     * Объединение юнитов в один
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    protected function uniteUnits(array $units)
    {
        $border_groups_of_units = $this->pseudo_code_driver->findBorderGroupsOfUnits($units);

        foreach ($border_groups_of_units as $found_pattern) {
            $start = $found_pattern->getStart();
            $end = $found_pattern->getEnd();
            $tokens = [];
            for ($i = $start; $i <= $end; $i++) {

                if (empty($units[$i])) {
                    throw new \LogicException('Unit with id = ' . var_export($i, true) . ' does not exists');
                }

                $tokens = array_merge($tokens, $units[$i]->getTokens());
                unset($units[$i]);
            }
            // пересоздание Unit'а
            $units[$start] = \Aot\Text\TextParserByTokenizer\Unit::create($tokens, $found_pattern->getType());
        }

        ksort($units);
        return array_values($units);
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
        $regex->addStartingCaret();
        return $regex->match($text);
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
     * @return string[][][]
     */
    protected function createSymbolsMap()
    {
        if ($this->sentences === []) {
            return [];
        }
        $symbols_map = [];
        $all_symbols = [];
        foreach ($this->sentences as $id_sentence => $sentence) {
            foreach ($sentence->getUnits() as $id_unit => $unit) {
                $symbols_from_unit = preg_split(static::SPLIT_REGEX, $unit, 0, PREG_SPLIT_NO_EMPTY);
                $all_symbols = array_merge($all_symbols, $symbols_from_unit);
                $count_of_symbols_from_unit = count($symbols_from_unit);
                $count_of_all_symbols = count($all_symbols);
                for ($i = $count_of_all_symbols - $count_of_symbols_from_unit; $i < $count_of_all_symbols; $i++) {
                    $symbols_map[$id_sentence][$id_unit][$i] = $all_symbols[$i];
                }
            }
        }
        return $symbols_map;
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
     * @return \Aot\Text\TextParserByTokenizer\Sentence[]
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @return string[][][]
     */
    public function getSymbolsMap()
    {
        return $this->symbols_map;
    }

    /**
     * @return string[][]
     */
    public function getSentenceWords()
    {
        $result = [];
        foreach ($this->sentences as $sentence) {
            $tmp = [];
            foreach ($sentence->getUnits() as $unit) {
                if ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE) {
                    continue;
                }
                $tmp[] = $unit->getStringRepresentation();
            }
            $result[] = $tmp;
        }

        return $result;
    }


    /**
     * @return string[]
     */
    public function getSentenceWordsAll()
    {
        $result = [];
        foreach ($this->sentences as $sentence) {
            foreach ($sentence->getUnits() as $unit) {
                if ($unit->getType() === \Aot\Text\TextParserByTokenizer\Unit::UNIT_TYPE_SPACE) {
                    continue;
                }
                $result[] = $unit->getStringRepresentation();
            }
        }

        return $result;
    }


    /**
     * @return string[]
     */
    public function getSentencesStrings()
    {
        $result = [];

        foreach ($this->sentences as $sentence) {

            $trimmed_units = $this->trimUnits($sentence->getUnits());

            $result[] = join('', $trimmed_units);
        }
        return $result;
    }

    /**
     * @param  \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @return  \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public static function trimUnits(array $units)
    {
        foreach ($units as $unit) {
            assert(is_a($unit, \Aot\Text\TextParserByTokenizer\Unit::class, true));
        }

        assert($units === array_values($units));

        $to_trim = [];
        for ($i = 0; $i < count($units); $i++) {
            if (in_array(
                $units[$i]->getType(),
                \Aot\Text\TextParserByTokenizer\TokenAndUnitRegistry::defaultTrimUnitType(),
                true
            )) {
                $to_trim[$i] = $i;
            }
            break;
        }

        if (count($to_trim) === count($units)) {
            return [];
        }

        for ($i = count($units) - 1; $i >= 0; $i--) {
            if (in_array(
                $units[$i]->getType(),
                \Aot\Text\TextParserByTokenizer\TokenAndUnitRegistry::defaultTrimUnitType(),
                true
            )) {
                $to_trim[$i] = $i;
            }
            break;
        }

        if (count($to_trim) === count($units)) {
            return [];
        }

        rsort($to_trim);

        $result = $units;
        foreach ($to_trim as $index) {
            unset($result[$index]);
        }

        return $result;
    }

    public function enableSymbolsMap()
    {
        $this->symbols_map_enabled = true;
    }
}