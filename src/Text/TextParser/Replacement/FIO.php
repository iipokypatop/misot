<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/07/15
 * Time: 00:19
 */

namespace Aot\Text\TextParser\Replacement;


class FIO extends Base
{
    protected function getPatterns()
    {
        return [
            "/[А-Я][а-я]+\\s*[А-Я]\\.\\s*[А-Я]\\./u",
            "/[А-Я]\\.\\s*[А-Я]\\.\\s*[А-Я][а-я]+/u",
        ];
    }
}