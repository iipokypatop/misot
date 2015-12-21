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
    use DataProvidersParseText;

    public function testLaunch()
    {
        $parser = TextParser::create();
        $this->assertInstanceOf(\Aot\Text\TextParser\TextParser::class, $parser);
    }

    public function testNewParser()
    {
        $text = 'Вчера {{ФИО}},     5   дней до н.э., я   [видел] Путина-Сибиряка В.В.,     он  н.э.  ┌гулял на крас╨ной площади и тд, и Б. Н. Ельцина.
        Потом я пошел в магазин за водкой, которую я обещал Медведеву Д. А.,  и т.п.
        Потом я позвонил по т.8(495)963-56-22 снял в банке 1 000 000 рублей,
        и мне дали 16,26 рублей?';
//        $text = 'Вчера {{ФИО}},     5   дней до н.э., я   [видел] Путина-Сибиряка В.В.';
        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
    }

    public function testNewParserShorts()
    {
        $this->markTestSkipped();
//        $text = 'Я опоздал сегодня на 5с., я увидел всех и др. и пр.
//        Я пошел и купил б/у машину за 20 млн 2000г. Я был к.т.н. и толпа из тыс. разных чел!
//        Потом позвонил по т 5992333';
//        $text = 'На 171-ом разъезде уцелело двенадцать дворов';
        $text = '[На] "западе" (в сырые ночи оттуда доносило тяжкий гул "артиллерии") обе; стороны,а потом подумал';
        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
    }


    /**
     * подсчет кол-ва предложений
     * @param $text
     * @param $expected_cnt_sentences
     * @dataProvider dataProviderSentences
     */
    public function testCountSentences($text, $expected_cnt_sentences)
    {
        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
        $cnt = count($parser->getSentences());
        $this->assertEquals($expected_cnt_sentences, $cnt);
    }


    /**
     * подсчет кол-ва слов в каждом предложении
     * @param $sentence
     * @param $expected_cnt_words
     * @dataProvider dataProviderSentencesForCountWords
     */
    public function testCountWords($sentence, $expected_cnt_words)
    {
        $parser = TextParser::create();
        $parser->execute($sentence);
        $parser->render();
        $cnt = 0;
        foreach ($parser->getSentenceWords() as $words) {
            $cnt += count($words);
        }
        $this->assertEquals($expected_cnt_words, $cnt);
    }

    /**
     * тест количества замен
     * @param $text
     * @param $expected_cnt_replacing
     * @dataProvider dataProviderSentencesForCountReplacing
     */
    public function testCountReplacing($text, $expected_cnt_replacing)
    {

        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
        $cnt = count($parser->getRegistry()->getRegistry());
        $this->assertEquals($expected_cnt_replacing, $cnt);
    }

    /**
     * тест замены, что должно было заменить, что не должно
     *
     * @param $text
     * @param $expected_replacing
     * @dataProvider dataProviderSentencesForRightReplacing
     */
    public function testRightReplacing($text, $expected_replacing)
    {
        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
        $diff = array_diff($parser->getRegistry()->getRegistry(), $expected_replacing);
        $this->assertEquals(0, count($diff)); // не должно быть расхождений

    }

    /**
     * тест на верную обратную подмену по шаблону
     *
     * @param $text
     * @param $expected_replacing
     * @dataProvider dataProviderSentencesForRightBackReplacing
     */
    public function testRightBackReplacing($text, $expected_replacing)
    {
        $parser = TextParser::create();
        $parser->execute($text);
        $parser->render();
        $sentence_words = [];
        foreach ($parser->getSentenceWords() as $sentence) {
            $sentence_words = array_merge($sentence_words, $sentence);

        }
//        $string = '[';
//        foreach ($sentence_words  as $word) {
//            $string .= "'{$word}',";
//        }
//        echo preg_replace("/\\,$/u", "]", $string) . "\n";

//        print_r($sentence_words);
//
//        print_r($expected_replacing);

        $diff = array_diff($sentence_words, $expected_replacing);
        $this->assertEquals(0, count($diff)); // не должно быть расхождений

    }


    public function testMap()
    {
        $text = <<<TXT
Вчера  ,     5   дней до н.э., я   [видел] Путина-Сибиряка В.В.
TXT;

        $parser = TextParser::create();
        $hook = \Aot\Text\TextParser\PostHooks\PositionMap::create();
        $parser->addPostHook($hook);
        $parser->execute($text);
        $parser->render();

        $result = $hook->getMap();

        $this->assertEquals(
            '[[["\u0412","\u0447","\u0435","\u0440","\u0430"],{"7":","},{"13":"5"},{"17":"\u0434","18":"\u043d","19":"\u0435","20":"\u0439"},{"22":"\u0434","23":"\u043e"},{"25":"\u043d","26":".","27":"\u044d","28":"."},{"29":","},{"31":"\u044f"},{"35":"["},{"36":"\u0432","37":"\u0438","38":"\u0434","39":"\u0435","40":"\u043b"},{"41":"]"},{"43":"\u041f","44":"\u0443","45":"\u0442","46":"\u0438","47":"\u043d","48":"\u0430","49":"-","50":"\u0421","51":"\u0438","52":"\u0431","53":"\u0438","54":"\u0440","55":"\u044f","56":"\u043a","57":"\u0430","58":" ","59":"\u0412","60":".","61":"\u0412","62":"."}]]',
            json_encode($result)
        );

    }
}