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
    /**
     * @var int[]
     */
    protected $token_types = [];

    /**
     * @var \Aot\Tokenizer\Token\Token[]
     */
    protected $tokens = [];

    /**
     * @var \Aot\Tokenizer\Token\Regex[][]
     */
    protected $regex_list = [];

    protected function __construct()
    {

    }

    /**
     * @return \Aot\Tokenizer\Base
     */
    public static function createDefault()
    {
        $ob = new static();

        $ob->token_types[] = \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_WORD;
        $ob->token_types[] = \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_PUNCTUATION;
        $ob->token_types[] = \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_OTHER;

        return $ob;
    }

    /**
     * @return \Aot\Tokenizer\Base
     */
    public static function createEmptyConfiguration()
    {
        $ob = new static();

        return $ob;
    }

    /**
     * @param string $string
     * @return int
     */
    public function tokenize($string)
    {
        assert(is_string($string));

        $this->assertUTF8($string);

        if (empty($string)) {
            return [];
        }

        $processing_string = $string;

        while (1) {

            $before_length = mb_strlen($processing_string);

            $processing_string = $this->read($processing_string);

            if ($before_length === mb_strlen($processing_string)) {
                throw new \LogicException("length must be reduced");
            }

            if (mb_strlen($processing_string, 'utf-8') === 0) {
                break;
            }
        }

        return count($this->tokens);
    }

    /**
     * @param $string
     */
    protected function assertUTF8($string)
    {

    }

    /**
     * @param  string $string
     * @return string
     */
    protected function read($string)
    {
        assert(is_string($string));

        if (empty($string)) {
            return '';
        }

        foreach ($this->token_types as $token_type_id) {

            if (!isset($this->regex_list[$token_type_id])) {
                $this->regex_list[$token_type_id] =
                    \Aot\Tokenizer\Token\TokenRegexRegistry::getTokenTypesByPattern($token_type_id);
            }

            /**
             * @var \Aot\Tokenizer\Token\Regex[]
             */
            $regex_list = $this->regex_list[$token_type_id];


            foreach ($regex_list as $regex) {

                $regex->addStartingCaret();

                $status = $regex->match($string);

                if (!$status) {
                    continue;
                }

                $matching_string = $regex->getLastMatching();

                $remainer_part_of_string = $this->cutFromString($string, $matching_string);

                $this->tokens[] = $this->buildToken($matching_string, $token_type_id);

                return $remainer_part_of_string;
            }
        }

        $regex = \Aot\Tokenizer\Token\Regex::create(
            \Aot\Tokenizer\Token\Regex::PATTERN_DOT
        );

        $status = $regex->match($string);

        if (!$status) {
            throw new \LogicException("must match to PATTERN_DOT");
        }

        $matching_string = $regex->getLastMatching();

        $this->tokens[] = $this->buildDefaultToken($matching_string);

        $remainer_part_of_string = $this->cutFromString($string, $matching_string);

        return $remainer_part_of_string;
    }

    protected function cutFromString($source_string, $string_to_cut)
    {
        assert(is_string($source_string));
        assert(is_string($string_to_cut));
        assert((mb_strlen($string_to_cut) <= mb_strlen($source_string)));

        if ('' === $string_to_cut) {
            return $source_string;
        }

        if ('' === $source_string) {
            return '';
        }

        return mb_substr(
            $source_string,
            mb_strlen($string_to_cut)
        );
    }

    protected function buildToken($text, $type)
    {
        return \Aot\Tokenizer\Token\Token::create(
            $text,
            $type
        );
    }

    protected function buildDefaultToken($text)
    {
        return \Aot\Tokenizer\Token\Token::create(
            $text,
            \Aot\Tokenizer\Token\TokenFactory::TOKEN_TYPE_OTHER
        );
    }

    /**
     * @return Token\Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param int $type
     */
    public function addTokenType($type)
    {
        assert(is_int($type));

        if (!in_array($type, \Aot\Tokenizer\Token\TokenFactory::getIds(), true)) {
            throw new \LogicException("unsupported token type " . var_export($type, 1));
        }

        if (in_array($type, $this->token_types, true)) {
            return;
        }

        $this->token_types[] = $type;
    }
}












