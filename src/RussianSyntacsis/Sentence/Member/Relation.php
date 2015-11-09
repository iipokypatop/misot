<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:25
 */

namespace Aot\RussianSyntacsis\Sentence\Member;


class Relation
{
    /** @var \Aot\RussianSyntacsis\Sentence\Member\Word */
    protected $main;
    /** @var \Aot\RussianSyntacsis\Sentence\Member\Word */
    protected $depended;

    /**
     * @param Word $main
     * @param Word $depended
     * @return Relation
     */
    public static function create(
        \Aot\RussianSyntacsis\Sentence\Member\Word $main,
        \Aot\RussianSyntacsis\Sentence\Member\Word $depended
    ) {
        $obj = new static();
        $obj->main = $main;
        $obj->depended = $depended;
        return $obj;
    }

    protected function __construct()
    {

    }
}