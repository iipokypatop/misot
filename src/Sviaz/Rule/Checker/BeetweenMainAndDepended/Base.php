<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.07.2015
 * Time: 10:41
 */

namespace Aot\Sviaz\Rule\Checker\BeetweenMainAndDepended;


class Base extends \Aot\Sviaz\Rule\Checker\Base
{
    public static function create()
    {
        $ob = new static();

        return $ob;
    }
}