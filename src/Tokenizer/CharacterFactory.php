<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 18:00
 */

namespace Aot\Tokenizer;


class CharacterFactory
{
    const PATTERN_ANY_CHARACTER = '/(.)/misu';

    /**
     * @var string
     */
    protected $string;

    /**
     * @var  string[]
     */
    protected $characters = [];

    public static function create($string)
    {
        assert(is_string($string));

        $ob = new static($string);

        return $ob;
    }

    /**
     * Base constructor.
     * @param string $string
     */
    public function __construct($string)
    {
        assert(is_string($string));

        $this->string = $string;
    }


    /**
     * @return bool
     */
    public function factory()
    {
        $result = preg_match_all(
            static::PATTERN_ANY_CHARACTER,
            $this->string,
            $matches,
            PREG_PATTERN_ORDER
        );

        if ($result === false || $result === 0) {
            return false;
        }

        foreach ($matches[1] as $match) {
            $this->characters[] = $match;
        }

        return true;
    }

    /**
     * @return \string[]
     */
    public function getIterator()
    {
        return $this->characters;
    }

}