<?php

namespace Aot\RussianSyntacsis\Punctuaciya;


/**
 * Created by PhpStorm.
 * User: Ivan
 */
abstract class Factory
{
    /**
     * @param $text
     * @return null|\Aot\RussianSyntacsis\Punctuaciya\Base
     */
    public static function build($text)
    {
        assert(is_string($text));
        switch ($text) {
            case '(':
                return \Aot\RussianSyntacsis\Punctuaciya\Skobki\Otkrivauchaya::create($text);
            case ')':
                return \Aot\RussianSyntacsis\Punctuaciya\Skobki\Zakrivauchaya::create($text);
            case ':':
                return \Aot\RussianSyntacsis\Punctuaciya\Dvoetochie::create($text);
            case '-':
                return \Aot\RussianSyntacsis\Punctuaciya\Tire::create($text);
            case ';':
                return \Aot\RussianSyntacsis\Punctuaciya\TochkaSZapiatoj::create($text);
            case '...':
                return \Aot\RussianSyntacsis\Punctuaciya\Troetochie::create($text);
            case '?':
                return \Aot\RussianSyntacsis\Punctuaciya\VoprositelnijZnak::create($text);
            case '!':
                return \Aot\RussianSyntacsis\Punctuaciya\VosklicatelnijZnak::create($text);
            case ',':
                return \Aot\RussianSyntacsis\Punctuaciya\Zapiataya::create($text);
            case '.':
                return \Aot\RussianSyntacsis\Punctuaciya\Tochka::create($text);
            default:
                return null;
        }
    }

    protected function __construct()
    {

    }
}
