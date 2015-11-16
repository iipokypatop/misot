<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 21.06.2015
 * Time: 22:10
 */

namespace Aot\RussianSyntacsis\Punctuaciya;


class Base implements \Aot\Unit
{
    protected $text;
    //public $pre_hash;

    /**
     * @param string $text
     * @return static
     */
    public static function create($text)
    {
        assert((is_string($text)));
        return new static($text);
    }

    protected function __construct($text)
    {
        $this->text = $text;
        //$this->pre_hash = spl_object_hash($this);
    }

    public function getText()
    {
        return $this->text;
    }

}