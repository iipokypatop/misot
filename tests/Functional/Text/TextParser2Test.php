<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 19:44
 */

namespace AotTest\Functional\Text;


use Aot\Text\TextParser2\TextParser;

class TextParser2Test extends \AotTest\AotDataStorage
{

    public function testToken()
    {
        $str = " Потом я пошел в магазин за водкой, которую я обещал Медведеву Д. А.,  и т.п.";

        $res = strtok($str, "м");


        var_export($res);

        $res = strtok(" ");

        var_export($res);
    }

}