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
    const TOKEN_TYPE_SPACE = 3;
    const TOKEN_TYPE_DASH = 4;
    const TOKEN_TYPE_NUMBER = 5;
    const TOKEN_TYPE_OTHER = 6;

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

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


}