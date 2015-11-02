<?php

namespace Aot\RussianSyntacsis\Punctuaciya;


/**
 * Created by PhpStorm.
 * User: Ivan
 */
class Factory
{
    const OTKRIVAUCHAYA_SKOBKA = 1;
    const ZAKRIVAUCHAYA_SKOBKA = 2;
    const DVOETOCHIE = 3;
    const TIRE = 4;
    const TOCHKASZAPIATOJ = 5;
    const TROETOCHIE = 6;
    const VOPROSITELNIJZNAK = 7;
    const VOSKLICATELNIJZNAK = 8;
    const ZAPIATAYA = 9;
    const TOCHKA = 10;

    protected static $instance;

    /**
     * @return \Aot\RussianSyntacsis\Punctuaciya\Factory
     */
    public static function getInstance(){
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * @return string[][]
     */
    public function getVariants()
    {
        return [
            static::OTKRIVAUCHAYA_SKOBKA => ['(', '[', '{'],
            static::ZAKRIVAUCHAYA_SKOBKA => [')', ']', '}'],
            static::DVOETOCHIE => [':'],
            static::TIRE => ['-'],
            static::TOCHKASZAPIATOJ => [';'],
            static::TROETOCHIE => ['...'],
            static::VOPROSITELNIJZNAK => ['?'],
            static::VOSKLICATELNIJZNAK => ['!'],
            static::ZAPIATAYA => [','],
            static::TOCHKA => ['.']
        ];
    }

    /**
     * @return \Aot\RussianSyntacsis\Punctuaciya\Base[]
     */
    public function getClasses()
    {
        return [
            static::OTKRIVAUCHAYA_SKOBKA => \Aot\RussianSyntacsis\Punctuaciya\Skobki\Otkrivauchaya::class,
            static::ZAKRIVAUCHAYA_SKOBKA => \Aot\RussianSyntacsis\Punctuaciya\Skobki\Zakrivauchaya::class,
            static::DVOETOCHIE => \Aot\RussianSyntacsis\Punctuaciya\Dvoetochie::class,
            static::TIRE => \Aot\RussianSyntacsis\Punctuaciya\Tire::class,
            static::TOCHKASZAPIATOJ => \Aot\RussianSyntacsis\Punctuaciya\TochkaSZapiatoj::class,
            static::TROETOCHIE => \Aot\RussianSyntacsis\Punctuaciya\Troetochie::class,
            static::VOPROSITELNIJZNAK => \Aot\RussianSyntacsis\Punctuaciya\VoprositelnijZnak::class,
            static::VOSKLICATELNIJZNAK => \Aot\RussianSyntacsis\Punctuaciya\VosklicatelnijZnak::class,
            static::ZAPIATAYA => \Aot\RussianSyntacsis\Punctuaciya\Zapiataya::class,
            static::TOCHKA => \Aot\RussianSyntacsis\Punctuaciya\Tochka::class,
        ];
    }


    /**
     * @param $text
     * @return null|\Aot\RussianSyntacsis\Punctuaciya\Base
     */
    public function build($text)
    {
        assert(is_string($text));
        
        foreach($this->getVariants() as $type => $chars ){
            if(in_array($text, $chars, true)){
                return forward_static_call_array([$this->getClasses()[$type], 'create'],[$text]);
            }
        }
        return null;
    }


    protected function __construct()
    {

    }
}
