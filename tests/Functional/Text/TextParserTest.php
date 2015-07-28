<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 19:44
 */

namespace AotTest\Functional\Text;


use Aot\Text\TextParser\TextParser;

class TextParserTest extends \AotTest\AotDataStorage
{

    public function testNewParser()
    {
        $text = 'Вчера,     5   дней до н.э., я   [видел] Путина В.В.,     он  н.э.  ┌гулял на крас╨ной площади и тд, и Б. Н. Ельцина.
        Потом я пошел в магазин за водкой, которую я обещал Медведеву Д. А.,  и т.п.
        Потом я позвонил по т.8(495)963-56-22 снял в банке 1 000 000 рублей,
        и мне дали 16,26 рублей?';
        $parser = TextParser::create();
        $parser->execute($text);
//        $str = $parser->render();
//        echo $str;
    }
}