<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 09/02/16
 * Time: 15:54
 */

namespace Aot\Text\TextParserByTokenizer\PseudoCode;


class FoundPatterns
{
    /** @var  int */
    protected $start;

    /** @var  int */
    protected $end;

    /** @var  int */
    protected $type;

    /**
     * @param int $start
     * @param int $end
     * @param int $type
     * @return \Aot\Text\TextParserByTokenizer\PseudoCode\FoundPatterns
     */
    public static function create($start, $end, $type)
    {
        return new static($start, $end, $type);
    }

    /**
     * @param int $start
     * @param int $end
     * @param int $type
     */
    protected function __construct($start, $end, $type)
    {
        assert(is_int($start) && is_int($end) && is_int($type));
        $this->start = $start;
        $this->end = $end;
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}