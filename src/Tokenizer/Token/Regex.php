<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 18:13
 */

namespace Aot\Tokenizer\Token;


class Regex
{
    const REGEX_MODIFIERS = 'su';
    const REGEX_DELIMITER = '#';
    const REGEX_PLUS = '+';
    const REGEX_STAR = '*';
    const REGEX_CARET = '^';

    const MASK_HEART_NAME = "heart";


    const PATTERN_DOT = '.';
    /**
     * @var string
     */
    protected $heart;
    protected $caret = '';
    protected $matches;

    /**
     * @param string $heart
     */
    protected function __construct($heart)
    {
        assert(is_string($heart));

        $this->heart = $heart;
    }

    /**
     * @param $heart
     * @return Regex
     */
    public static function create($heart)
    {
        $ob = new static($heart);

        return $ob;
    }

    /**
     * @param string $string
     * @return bool
     */
    public function match($string)
    {
        $res = preg_match(
            $this->buildPattern(),
            $string,
            $this->matches
        );

        if ($res === false) {
            return false;
        }

        return $res > 0;
    }

    /**
     * @return string
     */
    protected function buildPattern()
    {
        $heart =
            $this->caret .
            '(?<' . static::MASK_HEART_NAME . ">" .
            $this->heart .
            ")";


        return
            static::REGEX_DELIMITER.
            $heart.
            static::REGEX_DELIMITER.
            static::REGEX_MODIFIERS;

    }

    public function addStartingCaret()
    {
        $this->caret = static::REGEX_CARET;
    }

    public function getLastMatching()
    {
        return $this->matches[static::MASK_HEART_NAME];
    }


}