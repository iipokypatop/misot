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
    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $type;


    public $__debug_name;

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

        $this->__debug_name = \Aot\Tokenizer\Token\TokenFactory::getNames()[$this->type];
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

        if (!in_array($type, \Aot\Tokenizer\Token\TokenFactory::getIds(), true)) {
            throw new \LogicException("unsupported token type " . var_export($type, 1));
        }

        $ob = new static($text, $type);

        return $ob;
    }

    public function __toString()
    {
        return $this->text;
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