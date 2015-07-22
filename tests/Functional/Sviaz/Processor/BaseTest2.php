<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.07.2015
 * Time: 0:06
 */

namespace AotTest\Functional\Sviaz\Processor;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

class BaseTest2 extends \AotTest\AotDataStorage
{
    public function testLauch()
    {
        $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я ]/u', '', <<<TEXT


Моя борьба с домоуправлением закончилась полной капитуляцией управдома.

TEXT
        ));

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);

        $matrix = \Aot\Text\Matrix::create($slova);

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        $processor = \Aot\Sviaz\Processor\Base::create();

        $link_container = $processor->go(
            $normalized_matrix,
            [
                \Aot\Sviaz\Rule\Container::getRule_PrityazhMest_Susch(),
            ]
                /* array_merge([
                     \Aot\Sviaz\Rule\Container::getRule1(), \Aot\Sviaz\Rule\Container::getRule2(), \Aot\Sviaz\Rule\Container::getRule3(), \Aot\Sviaz\Rule\Container::getRule4(), \Aot\Sviaz\Rule\Container::getRule5(), \Aot\Sviaz\Rule\Container::getRule6(), \Aot\Sviaz\Rule\Container::getRule7(), \Aot\Sviaz\Rule\Container::getRule8(), \Aot\Sviaz\Rule\Container::getRule9(), \Aot\Sviaz\Rule\Container::getRule10(), \Aot\Sviaz\Rule\Container::getRule11(), \Aot\Sviaz\Rule\Container::getRule12(), \Aot\Sviaz\Rule\Container::getRule13(), \Aot\Sviaz\Rule\Container::getRule14(), \Aot\Sviaz\Rule\Container::getRule17(), \Aot\Sviaz\Rule\Container::getRule15(),

                     \Aot\Sviaz\Rule\Container::getRule_PerehGl_Susch(),
                     \Aot\Sviaz\Rule\Container::getRule_LichnoeMest_Pril(),
                     \Aot\Sviaz\Rule\Container::getRule_OtricMest_Gl(),
                     \Aot\Sviaz\Rule\Container::getRule_OtricMest_Prich(),


                     //\Aot\Sviaz\Rule\Container::getRule_UkazMest_Susch(),

                 ],
                     \Aot\Sviaz\Rule\Container::getRuleSuch1(),
                     \Aot\Sviaz\Rule\Container::getRuleSuchPadej()
                 )*/

        );

        foreach ($link_container as $sequence_index => $links) {
            $data = [];
            $data [] = $sequence_index;

            foreach ($links as $link) {
                $data[] =
                    $link->getMainSequenceMember()->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByClass(get_class($link->getMainSequenceMember()->getSlovo())) . ")"
                    . "->" .
                    $link->getDependedSequenceMember()->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByClass(get_class($link->getDependedSequenceMember()->getSlovo())) . ")";
            }

            echo join(" ", $data) . "\n";
        }

    }
}