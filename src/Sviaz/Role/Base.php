<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 14:47
 */

namespace Aot\Sviaz\Role;


class Base
{
    public static function create()
    {
        return new static();
    }
}