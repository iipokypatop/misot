<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.05.2016
 * Time: 13:59
 */

namespace AotTest\Functional\RussianMorphology;


class Issue3131Test extends \AotTest\AotDataStorage
{
    public function testIssue3131()
    {
        $text_1 = 'Посмотреть на неё';
        $sentences_1 = \Aot\Tools\ConvertTextIntoSlova::convert($text_1);
        /** @var \Aot\RussianMorphology\Slovo[][] $slova_1 */
        $slova_1 = $sentences_1[0]->getSlova();

        $text_2 = 'Посмотреть на нее';
        $sentences_2 = \Aot\Tools\ConvertTextIntoSlova::convert($text_2);
        /** @var \Aot\RussianMorphology\Slovo[][] $slova_2 */
        $slova_2 = $sentences_2[0]->getSlova();

        $this->assertTrue(count($slova_1) === count($slova_2));

        for ($i = 0; $i < count($slova_1); $i++) {
            $this->assertTrue(count($slova_1[$i]) === count($slova_2[$i]));
        }

        for ($i = 0; $i < count($slova_1[2]); $i++) {
            $this->assertTrue($slova_1[2][$i]->getInitialForm() === $slova_2[2][$i]->getInitialForm());
            $this->assertTrue($slova_1[2][$i]->getText() === $slova_2[2][$i]->getText());
        }
    }
}
