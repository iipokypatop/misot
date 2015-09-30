<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;




class SyntaxTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $filter_syntax=\Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        $filter_syntax->run("test");
    }
}