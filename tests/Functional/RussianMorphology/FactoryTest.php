<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;


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
//        $words = preg_split('/\s+/', <<<TEXT
//Убили хозяина-мастера
//TEXT
//        );

//        $text = 'Алиса-каприза пошла в магазин-намазин, чтобы купить телефон-патефон, по которому пообщается с Бобом-дурдомом';
        $text = 'Алиса-каприза пошла в магазин-намазин , чтобы купить телефон-патефон , по которому пообщается с Бобом-дурдомом';
        $words = preg_split('/\s+/', $text);

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
        ksort($slova);
        print_r($slova);
    }


    /**
     * @dataProvider dataProviderSentencesWithCompositeWords
     * @param string $sentence
     * @param string[] $items
     */
    public function testCompositeWords($sentence, $items)
    {
        $words = preg_split('/\s+/', $sentence);
        $slova = \Aot\RussianMorphology\Factory::getSlova($words);
        $i = 0;
        foreach ($items as $word_form => $initial_form) {
            if (preg_match("/^[\\-]+$/", $word_form)) {
                $i++;
                continue;
            }
            $this->assertNotEmpty($slova[$i]);
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


    public function dataProviderSentencesWithCompositeWords()
    {
        return [
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
                    '-' => '-',
                    'чтобы' => 'чтобы',
                    'купить' => 'купить',
                    'телефон-патефон' => 'телефон,патефон',
                    '--' => '-',
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