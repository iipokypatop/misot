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

        $this->assertEquals(0, count(array_diff($simple_words, $res[0])));
        $this->assertEquals(0, count(array_diff($res[0], $simple_words)));
        $this->assertEquals(0, count(array_diff($composite_words, $res[1])));
        $this->assertEquals(0, count(array_diff($res[1], $composite_words)));
    }

    /**
     * Проверка предложений с композитными словами
     * @dataProvider dataProviderSentencesWithCompositeWords
     * @param string $sentence
     * @param string[] $items
     */
    public function testSentencesWithCompositeWords($sentence, $items)
    {
        $words = preg_split('/\s+/', $sentence);
        $words = array_filter(
            $words,
            function ($word) {
                if ($word === ',') {
                    return false;
                }
                return true;
            }
        );
        $sorted_words = [];
        foreach ($words as $word) {
            $sorted_words[] = $word;
        }
        $slova = \Aot\RussianMorphology\Factory::getSlova($sorted_words);
        $i = 0;
        foreach ($items as $word_form => $initial_form) {
            $check_initial_form = false;
            /** @var \Aot\RussianMorphology\Slovo $slovo */
            foreach ($slova[$i] as $slovo) {
                $initial_form_slovo = mb_strtolower($slovo->getInitialForm(), 'utf-8');
                $this->assertEquals($word_form, $slovo->getText());
                if ($initial_form === $initial_form_slovo) {
                    $check_initial_form = true;
                }
            }
            // хотя бы для одного из вариантов Slovo будет совпадение по начальной форме
            $this->assertEquals(true, $check_initial_form);

            $i++;
        }
    }


    /**
     * @return array
     */
    public function dataProviderSentencesWithCompositeWords()
    {
        return [
            [
                '',
                []
            ],
            [
                'Убили хозяина-мастера',
                [
                    'убили' => 'убить',
                    'хозяина-мастера' => 'хозяин,мастер',
                ]
            ],
            [
                'Лесоруб увидел кабана-убийцу',
                [
                    'лесоруб' => 'лесоруб',
                    'увидел' => 'увидеть',
                    'кабана-убийцу' => 'кабан,убийца',
                ]
            ],
            [
                'Алиса-каприза пошла в магазин-намазин , чтобы купить телефон-патефон , по которому пообщается с Бобом-дурдомом',
                [
                    'Алиса-каприза' => 'алиса,каприз',
                    'пошла' => 'пойти',
                    'в' => 'в',
                    'магазин-намазин' => 'магазин,намазина',
                    'чтобы' => 'чтобы',
                    'купить' => 'купить',
                    'телефон-патефон' => 'телефон,патефон',
                    'по' => 'по',
                    'которому' => 'который',
                    'пообщается' => 'пообщаться',
                    'с' => 'с',
                    'Бобом-дурдомом' => 'боб,дурдом',
                ]
            ],
        ];
    }
}