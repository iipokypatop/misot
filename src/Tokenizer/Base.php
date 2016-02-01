<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 16:39
 */

namespace Aot\Tokenizer;


class Base
{
    /** @var \Aot\Tokenizer\CharacterFactory */
    protected $character_factory;

    /**
     * @var \Aot\Tokenizer\Token\Token[]
     */
    protected $tokens = [];

    /**
     * @var bool
     */
    protected $complicated_started = false;
    /**
     * @var string[]
     */
    protected $current_token_characters = [];

    protected $regex;

    protected function __construct()
    {
        $this->regex = \Aot\Tokenizer\Token\Regex::create();

    }

    public static function create()
    {
        $ob = new static();

        return $ob;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function tokenize($string)
    {
        assert(is_string($string));

        if (!$this->getCharacters($string)) {
            return false;
        }

        $characters = $this->character_factory->getIterator();

        $this->process($characters);

        return true;
    }

    protected function getCharacters($string)
    {
        $this->character_factory = \Aot\Tokenizer\CharacterFactory::create($string);

        return $this->character_factory->factory();
    }

    /**
     * @param string[] $characters
     * @return  \Aot\Tokenizer\Token\Token[]
     */
    protected function process(array $characters)
    {
        foreach ($characters as $character) {
            assert(is_string($character));
        }


        foreach ($characters as $character) {

            if (!$this->complicated_started) {
                if ($this->canBeInComplicatedToken($character)) {
                    $this->start();
                }
            }

            if ($this->complicated_started) {

                if (!$this->processAsPartOfComplicatedToken($character)) {

                    $this->endBuildToken();

                    $this->tokens[] = $this->regex->factoryTokenWithLastPatternType();

                    $this->tokens[] = $this->regex->factoryTokenFromCharacter(
                        $character
                    );

                    continue;
                }
            } else {

                $this->tokens[] = $this->regex->factoryTokenFromCharacter($character);

                continue;
            }
        }

        if ($this->complicated_started) {

            $this->endBuildToken();

            $this->tokens[] = $this->regex->factoryTokenWithLastPatternType();
        }

    }

    /**
     * @param string $character
     * @return bool
     */
    protected function canBeInComplicatedToken($character)
    {
        return $this->regex->charCanBeComplicated($character);
    }

    protected function start()
    {
        if ($this->complicated_started) {
            throw new \LoggerException("already started");
        }

        $this->regex->reset();

        $this->complicated_started = true;

        $this->current_token_characters = [];
    }

    /**
     * @param string $character
     * @return bool|void
     * @throws \LoggerException
     */
    protected function processAsPartOfComplicatedToken($character)
    {
        if (empty($this->current_token_characters)) {
            $this->current_token_characters[] = $character;
            return true;
        }

        $prev = $this->regex->stringCanBeComplicated($this->current_token_characters);

        $this->current_token_characters[] = $character;

        $current = $this->regex->stringCanBeComplicated($this->current_token_characters);

        if (!$prev) {
            throw new \LogicException("строка должна быть частью составного токена");
        }

        if ($prev && $current) {
            return true;
        }

        if ($prev && !$current) {

            return false;
        }
    }

    protected function endBuildToken()
    {
        if (!$this->complicated_started) {
            throw new \LoggerException("must be started");
        }

        $this->complicated_started = false;
    }

    /**
     * @return Token\Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param string $character
     */
    protected function processAsSimpleToken($character)
    {
        assert(is_string($character));


    }

    /**
     * @param string $character
     * @throws \LoggerException
     */
    protected function push($character)
    {
        if (!$this->complicated_started) {
            throw new \LoggerException("must be started");
        }

        assert(is_string($character));

        $this->current_token_characters[] = $character;
    }
}