<?php

namespace Aot\Graph\Slovo;


class TextPosition extends \SplObjectStorage
{
    /** @var  int */
    protected $position_in_sentence;

    /** @var  int */
    protected $sentence;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @param int $position_in_sentence
     * @param int $sentence_id
     */
    public function add(\Aot\Graph\Slovo\Vertex $vertex, $sentence_id, $position_in_sentence)
    {
        $this->attach($vertex, [$position_in_sentence, $sentence_id]);
    }

    /**
     * @param \Aot\Graph\Slovo\Vertex $vertex
     * @return int[]
     */
    public function get(\Aot\Graph\Slovo\Vertex $vertex)
    {
        return $this->offsetGet($vertex);
    }


}