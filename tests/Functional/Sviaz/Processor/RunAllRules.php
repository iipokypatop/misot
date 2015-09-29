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

class RunAllRules extends \AotTest\AotDataStorage
{

    public function dataProviderRules()
    {
        return [
            ['getRule_PerehGl_Susch'],
            ['getRule_LichnoeMest_Pril'],
            ['getRule_OtricMest_Gl'],
            ['getRule_OtricMest_Prich'],
            ['getRule_PrityazhMest_Susch'],
            ['getRule_UkazMest_Susch'],
            ['getSuschImenitPadeszh_Gl_Prich'],
            ['getLichnoeMestImenitPadeszh_Gl_Prich'],
            ['getRule_Susch_GlBit_GlInf'],
            ['getRule_Mest_Gl_Narech'],
            ['Rule_Gl_Deepr'],
            ['getRule_LichnoeMest_GlagBit_KrPril'],
            ['getRule_Susch_GlagBit_KrPril'],
            # todo: no method
//            ['getRule_KrPril_Susch'],
            ['getRule_Susch_PoryadkChisl'],
            ['getRule_Pril_Narech'],
            ['getRule_Narech_Narech'],
            ['getRule_Narech_Narech'],
            ['getRule_Gl_Narech'],
            ['getRule_Gl_DefisNarech'],
        ];
    }

    /**
     * @param $name_rule
     * @dataProvider dataProviderRules
     */
    public function testLaunch($name_rule)
    {
//        echo "\n*****************************\n" . $name_rule . "\n";

        $text = 'В апреле 1874 года первая жена Шишкина умирает.';
        $text = 'Главное отличие творчества Шишкина - это его ошеломляющая открытость, с которой он рассказывал своими картинами о любви к родному краю.';
        $rule = Container::$name_rule();

        $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я- ]/u', '', $text));

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);

        $matrix = \Aot\Text\Matrix::create($slova);

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        $processor = \Aot\Sviaz\Processor\Base::create();

        $have_link = false; // имеет связь
        // запускаем правило
        if (is_array($rule)) {
            foreach ($rule as $key => $rule_el) {
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


        if ($have_link) {
            print_r([$name_rule => $data]);
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

        return $data;
    }
}