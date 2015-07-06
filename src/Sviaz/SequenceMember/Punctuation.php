<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:25
 */

namespace Aot\Sviaz\SequenceMember;


class Punctuation extends Base
{
    /** @var  \Aot\RussianSyntacsis\Punctuaciya\Base */
    protected $punctuaciya;

    /**
     * Punctuation constructor.
     * @param  \Aot\RussianSyntacsis\Punctuaciya\Base $punctuaciya
     */
    public function __construct(\Aot\RussianSyntacsis\Punctuaciya\Base $punctuaciya)
    {
        $this->punctuaciya = $punctuaciya;
    }

    public static function create(\Aot\RussianSyntacsis\Punctuaciya\Base $punctuaciya)
    {
        return new static($punctuaciya);
    }
}