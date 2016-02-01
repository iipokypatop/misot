<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 026, 26, 01, 2016
 * Time: 17:14
 */

namespace Aot\Tokenizer\Token;


class Token
{
    const TOKEN_TYPE_WORD = 1;
    const TOKEN_TYPE_PUNCTUATION = 2;
    const TOKEN_TYPE_OTHER = 3;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var int
     */
    protected $type;

    /**
     * Token constructor.
     * @param string $text
     * @param int $type
     */
    protected function __construct($text, $type)
    {
        assert(is_string($text));
        assert(is_int($type));

        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @param string $text
     * @param int $type
     * @return static
     */
    public static function create($text, $type)
    {
        assert(is_string($text));
        assert(is_int($type));

        $ob = new static($text, $type);

        return $ob;
    }


}