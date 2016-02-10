<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 10/02/16
 * Time: 10:29
 */

namespace Aot\Text\TextParserByTokenizer;


class Sentence
{
    /** @var  \Aot\Text\TextParserByTokenizer\Unit[] */
    protected $units;

    /** @var int  */
    protected $id;

    /**
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @param int $id
     * @return \Aot\Text\TextParserByTokenizer\Sentence
     */
    public static function create(array $units, $id)
    {
        return new static($units, $id);
    }

    /**
     * Sentence constructor.
     * @param \Aot\Text\TextParserByTokenizer\Unit[] $units
     * @param int $id
     */
    protected function __construct(array $units, $id)
    {
        assert(is_int($id));

        foreach ($units as $unit) {
            assert(is_a($unit, \Aot\Text\TextParserByTokenizer\Unit::class, true));
        }

        $this->units = $units;
        $this->id = $id;
    }


    public function __toString()
    {
        return join('', $this->units);
    }

    public function getPrettyString(){
        # TODO: форматирование строки: удаление лишних пробелов и тд
    }


    /**
     * @return \Aot\Text\TextParserByTokenizer\Unit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

}