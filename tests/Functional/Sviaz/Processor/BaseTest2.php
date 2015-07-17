<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.07.2015
 * Time: 0:06
 */

namespace AotTest\Functional\Sviaz\Processor;


class BaseTest2 extends \AotTest\AotDataStorage
{
    public function testLauch()
    {
        $words = preg_split('/\s+/', preg_replace('/[^а-яА-Я ]/u', '', <<<TEXT
После установки JSitemap вам не придётся ждать момента, когда поисковик просмотрит весь ваш сайт вместо этого вся информация сразу сможет отправиться в базы поисковиков.
TEXT
        ));

        $slova = \Aot\RussianMorphology\Factory::getSlova($words);

        $matrix = \Aot\Text\Matrix::create($slova);

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        $processor = \Aot\Sviaz\Processor\Base::create();

        $link_container = $processor->go(
            $normalized_matrix,
            array_merge([
                \Aot\Sviaz\Rule\Container::getRule1(),
                \Aot\Sviaz\Rule\Container::getRule2(),
                \Aot\Sviaz\Rule\Container::getRule3(),
                \Aot\Sviaz\Rule\Container::getRule4(),
                \Aot\Sviaz\Rule\Container::getRule5(),
            ], \Aot\Sviaz\Rule\Container::getRuleSuch1()
            )
        );

        foreach ($link_container as $sequence_index => $links) {
            $data = [];
            $data [] = $sequence_index;

            foreach ($links as $link) {
                $data[] =
                    $link->getMainSequenceMember()->getSlovo()->getText()
                    . "->" .
                    $link->getDependedSequenceMember()->getSlovo()->getText();
            }

             echo join(" ", $data) . "\n";
        }

    }
}