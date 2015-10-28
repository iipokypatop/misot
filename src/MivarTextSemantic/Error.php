<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 014, 14.10.2015
 * Time: 14:10
 */

namespace Aot\MivarTextSemantic;


class Error
{
    public static function error($message)
    {
        echo var_export($message);
    }
}