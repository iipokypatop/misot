<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;

use MivarTest\PHPUnitHelper;


class FactoryTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $words = preg_split('/\s+/', <<<TEXT
Большая ываываывааыва пять часть средств ушла на десять приобретение картины итальянского художника
TEXT
        );

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
    }


    public function testAbbrAndNumbers()
    {
        $this->markTestSkipped("В ожидании решения задач №2308 и №2310");
        /**
         * TODO:
         *
         * $text = 'Мама заплатила 22 рубля';
         * $text = 'Мама заплатила двадцать два рубля';
         */
        $text = 'МИВАР 22';
        $words = preg_split('/\s+/', $text);

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
    }


    public function testLaunchWithSplittedWords()
    {
        $text = 'Алиса-каприза пошла в магазин-намазин , чтобы купить телефон-патефон , по которому пообщается с Бобом-дурдомом';
        $words = preg_split('/\s+/', $text);

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
    }


    /**
     * Проверка метода разделения массива слов на группы
     */
    public function testSplitWordsInSimpleAndCompositeGroups()
    {
        $res = \Aot\RussianMorphology\Factory2\CompositeWordProcessor::splitArrayWords(
            [
                'Алиса-каприза',
                'в',
                'папа',
                'loli-poli',
                'человек-убийца',
                '-',
                'телефон-патефон',
                'топор',
                'green',
                'лес',
            ]
        );

        $simple_words = [
            'в',
            'папа',
            '-',
            'топор',
            'green',
            'лес',
        ];

        $composite_words = [
            'Алиса-каприза',
            'loli-poli',
            'человек-убийца',
            'телефон-патефон',
        ];

        $res_simple = array_values($res[0]);
        $res_composite = array_values($res[1]);
        $this->assertEquals($simple_words, $res_simple);
        $this->assertEquals($composite_words, $res_composite);
    }
}