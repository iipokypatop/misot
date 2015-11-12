<?php

namespace Aot\RussianSyntacsis\Sentence\Member\Role;


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
            static::PODLEZHACHEE => \Aot\RussianSyntacsis\Sentence\Member\Role\Podlezhachee::class,
            static::SKAZUEMOE => \Aot\RussianSyntacsis\Sentence\Member\Role\Skazuemoe::class,
        ];
    }
}