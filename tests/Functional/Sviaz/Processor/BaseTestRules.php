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
            // 'Поставил пылесос.'
            /*[
                'getRule_PerehGl_Susch',
                [
                    'Человек поставил пылесос.' => true,
                ]
            ],

            // 'Он красивый и умный.', 'Она красивая и добрая.'
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

            // 'Никто не был замечен.', 'Никто не забыт, ничто не забыто.', 'Никто не будет загнан.'
            [
                'getRule_OtricMest_Prich',
                [
                    'Никто не был замечен в краже варенья из палатки.' => true,
                    'Никто не забыт, ничто не забыто.' => false,
                    'Никто не будет загнан в угол офиса.' => true
                ]
            ],

            // 'Мои университеты.', 'Моя борьба.', 'Его взгляд.'
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
            //
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
            ],*/
            //
            [
                'getRule_KrPril_Susch',
                [
                    'Плох тот администратор' => true,
                    'Плох тот администратор, что не использует бубен.' => true,
                    'Плох тот солдат, который не мечтает стать генералом.' => true
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

            $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я ]/u', '', $text));

            $slova = \Aot\RussianMorphology\Factory::getSlova($words);

            $matrix = \Aot\Text\Matrix::create($slova);

            $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

            $processor = \Aot\Sviaz\Processor\Base::create();

            $have_link = false; // имеет связь
            // запускаем правило
            if (count($rule) > 1) {
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

    protected function getStrRule($processor, $normalized_matrix, $rule)
    {

        $link_container = $processor->go($normalized_matrix, [$rule]);

        $data = [];
        foreach ($link_container as $sequence_index => $links) {

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

        if (!empty($data)) {
            echo " \n Связи:\n";
            foreach ($data as $str_link => $count_links) {
                echo $str_link . " - count_links: $count_links \n";
            }
            echo "\n";
        } else {
            echo " \n Связи:\n - \n";
        }
        return $data;
    }


}