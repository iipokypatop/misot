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
        # получаем подделку Aot\RussianMorphology\Factory
        $factory = $this->getMock(\Aot\RussianMorphology\Factory::class, ['create'], [], '', false);
        $res = PHPUnitHelper::callProtectedMethod($factory, 'splitArrayWords', [
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


    public function testFactorySimpleWords()
    {
        $items = [
            'пошла' => 'пойти',
            'чтобы' => 'чтобы',

            'Алиса-каприза' => 'алиса,каприз',
            'магазин-намазин' => 'магазин,намазина',
        ];

        $slova = \AotTest\Functional\RussianMorphology\FactoryTestStub::getSlova(
            array_values($items)
        );


        $items = array_values($items);

        $i = 0;
        /** @var $slovo \Aot\RussianMorphology\Slovo[] */
        foreach ($slova as $slovo) {
            $this->assertInstanceOf(\Aot\RussianMorphology\Slovo::class, $slovo[0]);
            $this->assertEquals($items[$i], $slovo[0]->getText());
            $this->assertEquals($items[$i], $slovo[0]->getInitialForm());
            $i++;
        }
    }
}

class WdwDriverTestStub extends \Aot\RussianMorphology\WdwDriver
{
    public function createWdwSpace(array $words)
    {

        $simple_words1 = [
            'пойти',
        ];
        $simple_words2 = [
            'чтобы',
        ];

        $composite_words1 = [
            'алиса,каприз',
        ];

        $composite_words2 = [
            'магазин,намазина',
        ];


        if (
            $words === $simple_words1
            || $words === $simple_words2
            || $words === $composite_words1
            || $words === $composite_words2
        ) {
            $result = [];
            foreach ($words as $word) {
                $result[][] = $point = new \PointWdw();
                $point->dw = $dw = new \DictionaryWord;
                $dw->id_word_class = \Aot\MivarTextSemantic\Constants::ADVERB_CLASS_ID;
                $dw->word_form = $word;
                $dw->initial_form = $word;
            }
            return $result;

        }

        throw new \LogicException("must not be here");
    }
}

class FactoryTestStub extends \Aot\RussianMorphology\Factory
{
    /**
     * @return \Aot\RussianMorphology\WdwDriver
     */
    protected static function getDriver()
    {
        return WdwDriverTestStub::create();
    }
}
