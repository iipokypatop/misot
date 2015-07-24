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
            [
                'getRule_PerehGl_Susch',
                [
                    'Человек поставил пылесос.'
                ]
            ],

            // 'Он красивый и умный.', 'Она красивая и добрая.'
            [
                'getRule_LichnoeMest_Pril',
                [
                    'Он красивый и умный.',
                    'Она, к большому счастью, красивая и добрая.'
                ]
            ],

            //
            [
                'getRule_OtricMest_Gl',
                [
                    'Никто не стал.',
                    'Никто не будет.',
                    'Никто не любит.'
                ]
            ],

            // 'Никто не был замечен.', 'Никто не забыт, ничто не забыто.', 'Никто не будет загнан.'
            [
                'getRule_OtricMest_Prich',
                [
                    'Никто не был замечен в краже варенья из палатки.',
                    'Никто не забыт, ничто не забыто.',
                    'Никто не будет загнан в угол офиса.'
                ]
            ],

            // 'Мои университеты.', 'Моя борьба.', 'Его взгляд.'
            [
                'getRule_PrityazhMest_Susch',
                [
                    'Мои университеты были наивны.',
                    'Моя борьба с домоуправлением закончилась полной капитуляцией управдома.',
                    'По твоим словам получается, что динозавры за углом.',
                    'Наши танки быстрее ваших, но медленнее, чем их ракеты.'
                ]
            ],

            // 'Это ружье.', 'Каков совет.', 'Таков ответ.', 'Такими душками.'
            [
                'getRule_UkazMest_Susch',
                [
                    'Это ружье слишком долго висит на стене.',
                    'Каков совет, таков и ответ.',
                    'Они стали такими душками.'
                ]
            ],
            //
            [
                'getSuschImenitPadeszh_Gl_Prich',
                [
                    'Солдат был ранен в бою.',
                    'Девушка была накрашена.',
                    'Корыто было покрашено.',
                    'Ученики были научены.'
                ]
            ],
            //
            [
                'getLichnoeMestImenitPadeszh_Gl_Prich',
                [
                    'Он был ранен в бою.',
                    'Она была накрашена.',
                    'Оно было покрашено.',
                    'Они были научены.'
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


        foreach ($texts as $text) {
            echo "\n Текст:\n \t" . $text . " \n";

            $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я ]/u', '', $text));

            $slova = \Aot\RussianMorphology\Factory::getSlova($words);

            $matrix = \Aot\Text\Matrix::create($slova);

            $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

            $processor = \Aot\Sviaz\Processor\Base::create();

            // запускаем правило
            if (count($rule) > 1) {
                foreach ($rule as $key => $rule_el) {
                    echo "\n" . $key;
                    $this->getStrRule($processor, $normalized_matrix, $rule_el);
                }
            } else {

                $this->getStrRule($processor, $normalized_matrix, $rule);
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
    }


}