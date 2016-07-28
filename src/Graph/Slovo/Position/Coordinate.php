<?php

namespace Aot\Graph\Slovo\Position;


class Coordinate
{
    /** @var  int */
    protected $sentence_id;

    /** @var  int */
    protected $position_in_sentence;

    /**
     * @param int $sentence_id
     * @param int $position_in_sentence
     * @return static
     */
    public static function create($sentence_id, $position_in_sentence)
    {
        assert(is_int($sentence_id));
        assert(is_int($position_in_sentence));
        return new static($sentence_id, $position_in_sentence);
    }

    /**
     * @param $sentence_id
     * @param $position_in_sentence
     */
    protected function __construct($sentence_id, $position_in_sentence)
    {
        assert(is_int($sentence_id));
        assert(is_int($position_in_sentence));
        $this->sentence_id = $sentence_id;
        $this->position_in_sentence = $position_in_sentence;
    }

    /**
     * @return int
     */
    public function getSentenceId()
    {
        return $this->sentence_id;
    }

    /**
     * @return int
     */
    public function getPositionInSentence()
    {
        return $this->position_in_sentence;
    }
}