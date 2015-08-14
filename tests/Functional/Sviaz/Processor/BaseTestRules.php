<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.07.2015
 * Time: 0:06
 */

namespace AotTest\Functional\Sviaz\Processor;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\Sviaz\Rule\Container;

class BaseTestRules extends \AotTest\AotDataStorage
{

    public function dataProviderRulesAndTexts()
    {
        return [
            //
            [
                'getRule_PerehGl_Susch',
                [
                    'Человек поставил пылесос.' => true,
                ]
            ],

            //
            [
                'getRule_LichnoeMest_Pril',
                [
                    'Он красивый и умный.' => true,
                    'Она, к большому счастью, красивая и добрая.' => true
                ]
            ],

            //
            [
                'getRule_OtricMest_Gl',
                [
//                    'Никто не стал.' => true,
//                    'Никто не будет.' => true,
                    'Никто не любит.' => true
                ]
            ],

            //
            [
                'getRule_OtricMest_Prich',
                [
                    'Никто не был замечен в краже варенья из палатки.' => true,
                    'Никто не забыт, ничто не забыто.' => false,
                    'Никто не будет загнан в угол офиса.' => true
                ]
            ],

            //
            [
                'getRule_PrityazhMest_Susch',
                [
                    'Мои университеты были наивны.' => true,
                    'Моя борьба с домоуправлением закончилась полной капитуляцией управдома.' => true,
                    'По твоим словам получается, что динозавры за углом.' => true,
                    'Наши танки быстрее ваших, но медленнее, чем их ракеты.' => true
                ]
            ],

            // 'Это ружье.', 'Каков совет.', 'Таков ответ.', 'Такими душками.'
            [
                'getRule_UkazMest_Susch',
                [
                    'Это ружье слишком долго висит на стене.' => true,
                    'Каков совет, таков и ответ.' => true,
                    'Они стали такими душками.' => true,
                ]
            ],
            //
            [
                'getSuschImenitPadeszh_Gl_Prich',
                [
                    'Солдат был ранен в бою.' => true,
                    'Девушка была накрашена.' => true,
                    'Корыто было покрашено.' => true,
                    'Ученики были научены.' => true
                ]
            ],
            //
            [
                'getLichnoeMestImenitPadeszh_Gl_Prich',
                [
                    'Он был ранен в бою.' => true,
                    'Она была накрашена.' => true,
                    'Оно было покрашено.' => true,
                    'Они были научены.' => true
                ]
            ],
            //
            [
                'getRule_Susch_GlBit_GlInf',
                [
                    'Задача была выжить.' => true,
                    'Задача у него была постараться выжить' => true,
                ]
            ],
            //fail
            [
                'getRule_Mest_Gl_Narech',
                [
                    'Все у нас будет хорошо.' => true,
                    'Все будет у нас хорошо.' => true,
                    'Будет все у нас хорошо.' => true,
                    'Все у нас была хорошо.' => false,
                    'Где у нас хорошо будет.' => false
                ]
            ],

            //
            [
                'Rule_Gl_Deepr',
                [
                    'Звеня и подскакивая, пятак упал на мостовую.' => true,
                    'Человек спрятался.' => false,
                ]
            ],
            //
            [
                'getRule_LichnoeMest_GlagBit_KrPril',
                [
                    'Она была умна и красива.' => true,
                    'Умна она была и красива.' => true,
                    'Была умна она и красива.' => true,
                    'Была умна и красива она.' => true,
                    'Он был умен, но уродлив.' => true,
                    'Они были красивы.' => true,
                    'Она был умен, но уродлив.' => false,
                    'Человек спрятался.' => false,
                ]
            ],
            //
            [
                'getRule_Susch_GlagBit_KrPril',
                [
                    'Девушка была умна и красива.' => true,
                    'Умна девушка была и красива.' => true,
                    'Была умна девушка и красива.' => true,
                    'Была умна и красива девушка.' => true,
                    'Инженер был умен, но уродлив.' => true,
                    'Девушки были красивы.' => true,
                    'Она был умен, но уродлив.' => false,
                    'Человек спрятался.' => false,
                ]
            ],
            // fail
//            [
//                'getRule_KrPril_Susch',
//                [
//                    'Плох тот администратор' => true,
//                    'Плох тот администратор, что не использует бубен.' => true,
//                    'Плох тот солдат, который не мечтает стать генералом.' => true
//                ]
//            ],

            [
                'getRule_Susch_PoryadkChisl',
                [
                    'Первый с правого фланга солдат вышел из строя.' => true,
                    'Первый строй с правого фланга солдат вышел из строя.' => true // на самом деле false, если бы member отрабатывал
                ]
            ],

            //
            [
                'getRule_Pril_Narech',
                [
                    'Мальчик был очень находчивым.' => true,
                    'Небо над морем стало неестественно бурым.' => true,
                    'Красивые волны неестественно поднимались.' => false
                ]
            ],

            //
            [
                'getRule_Narech_Narech',
                [
                    'Мне очень страшно.' => true,
                    // когда в препроцессоре сделаем привязку частицы не к последующему слову, то можно раскомментить
//                  'Мне совсем не страшно идти в лес.' => true,
                ]
            ],

            //
            [
                'getRule_Narech_Narech',
                [
                    'Мне очень страшно.' => true,
//                    'Мне совсем не страшно идти в лес.' => true, // когда в препроцессоре сделаем привязку частицы не к последующему слову, то можно раскомментить
                ]
            ],
            //
            [
                'getRule_Gl_Narech',
                [
                    'Они вдесятером потянули за канат' => true,
                ]
            ],

            //
            [
                'getRule_Gl_DefisNarech',
                [
                    'Он сказал точь-в-точь то же самое, что и неделю назад' => true,
                ]
            ],

        ];
    }

    /**
     * @param $name_rule
     * @param array $texts
     * @dataProvider dataProviderRulesAndTexts
     */
    public function testLaunch($name_rule, $texts)
    {
        echo "\n*****************************\n" . $name_rule . "\n";

        $rule = Container::$name_rule();

        foreach ($texts as $text => $must_have_link) {
            echo "\n Текст:\n \t" . $text . " \n";

            $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я- ]/u', '', $text));

            $slova = \Aot\RussianMorphology\Factory::getSlova($words);

            $matrix = \Aot\Text\Matrix::create($slova);

            $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

            $processor = \Aot\Sviaz\Processor\Base::create();

            $have_link = false; // имеет связь
            // запускаем правило
            if (is_array($rule)) {
                foreach ($rule as $key => $rule_el) {
                    echo "\n" . $key;
                    $data = $this->getStrRule($processor, $normalized_matrix, $rule_el);
                    if (!empty($data)) {
                        $have_link = true;
                    }
                }
            } else {
                $data = $this->getStrRule($processor, $normalized_matrix, $rule);
                if (!empty($data)) {
                    $have_link = true;
                }
            }

            if ($have_link && !$must_have_link) {
                $this->fail("Возникла связь там, где ее быть не должно.\nПравило: " . $name_rule . "\nТекст: " . $text);
            } elseif (!$have_link && $must_have_link) {
                $this->fail("Не возникла связь там, где она должна быть.\nПравило: " . $name_rule . "\nТекст: " . $text);
            }

        }

    }

    /**
     * @param \Aot\Sviaz\Processor\Base $processor
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @param $rule
     * @return array
     */
    protected function getStrRule(\Aot\Sviaz\Processor\Base $processor, \Aot\Text\NormalizedMatrix $normalized_matrix, $rule)
    {
        //todo переделать на $this->pretty()
        $sequences = $processor->go($normalized_matrix, [$rule]);



        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }

        $data = [];
        foreach ($sviazi_container as $sequence_index => $links) {


            foreach ($links as $link) {

                $data_str =
                    $link->getMainSequenceMember()->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByClass(get_class($link->getMainSequenceMember()->getSlovo())) . ")"
                    . "->" .
                    $link->getDependedSequenceMember()->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByClass(get_class($link->getDependedSequenceMember()->getSlovo())) . ")";

                if (empty($data[$data_str])) {
                    $data[$data_str] = 1;
                } else {
                    $data[$data_str]++;
                }
            }

        }

        $echo = [];
        if (!empty($data)) {
            $echo[]= " \n Связи:\n";
            foreach ($data as $str_link => $count_links) {
                echo $str_link . " - count_links: $count_links \n";
            }
            $echo[] = "\n";
        } else {
            $echo[] = " \n Связи:\n - \n";
        }

        //echo join("\n", $echo);

        return $data;
    }
}