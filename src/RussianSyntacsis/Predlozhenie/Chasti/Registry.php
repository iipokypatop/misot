<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:16
 */

namespace Aot\RussianSyntacsis\Predlozhenie\Chasti;

//use Aot\Registry\Uploader;

class Registry
{
   // use Uploader;

    const PODLEZHACHEE = 11;
    const SKAZUEMOE = 12;


    public static function getNames()
    {
        return [
            static::PODLEZHACHEE => 'подлежащее',
            static::SKAZUEMOE => 'сказуемое',
        ];
    }

    public static function getClasses()
    {
        return [
            static::PODLEZHACHEE => \Aot\RussianSyntacsis\Predlozhenie\Chasti\Podlezhachee::class,
            static::SKAZUEMOE => \Aot\RussianSyntacsis\Predlozhenie\Chasti\Skazuemoe::class,
        ];
    }



}