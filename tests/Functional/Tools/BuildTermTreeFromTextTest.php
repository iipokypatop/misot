<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


class BuildTermTreeFromTextTest extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        $text = "Толстуха собралась стирать пижаму. Папа пропал, цепь привлекла глаз. Полка.";
        $text = "Мальчик пошёл охотиться и взял с собой лук";
        $res = \Aot\Tools\BuildTermTreeFromText::run($text);
        //print_r($res);
    }


}