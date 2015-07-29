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
        $text = 'Вчера ФИО,     5   дней до н.э., я   [видел] Путина-Сибиряка В.В.,     он  н.э.  ┌гулял на крас╨ной площади и тд, и Б. Н. Ельцина.
        Потом я пошел в магазин за водкой, которую я обещал Медведеву Д. А.,  и т.п.
        Потом я позвонил по т.8(495)963-56-22 снял в банке 1 000 000 рублей,
        и мне дали 16,26 рублей?';
        $parser = TextParser::create();
        $parser->execute($text);
//        $str = $parser->render();
//        echo $str;
    }

    public function testNewParserShorts()
    {
        $text = 'Я опоздал сегодня на 5с., я увидел всех и др. и пр.
        Я пошел и купил б/у машину за 20 млн 2000г. Я был к.т.н. и толпа из тыс. разных чел.
        Потом позвонил по т 5992333';
        $parser = TextParser::create();
        $parser->execute($text);
    }
}