<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.05.2016
 * Time: 9:24
 */
namespace AotTest\Functional\Sviaz\Processor\AotGraph;

class Issue3363Test extends \AotTest\AotDataStorage
{
    // http://redmine.mivar.ru/issues/3363
    public function testIssue()
    {
        $words = [
            'Лёша',
            'поймал',
            '23',
            'покемона',
            'а',
            'купил',
            '666',
            ',',
            'но',
            'всем',
            'сказал',
            ',',
            'что',
            'поймал',
            '1000000',
            'покемонов',
            '.',
        ];

        $aot_graph = \Aot\Sviaz\Processors\AotGraph\Base::create();
        $graph = $aot_graph->runBySentenceWords($words);
        $texts = [];
        foreach ($graph->getVerticesCollection() as $item) {
            $texts[] = $item->getSlovo()->getText();
        }

        $expected_texts = [
            $words[0],
            $words[1],
            $words[2],
            $words[3],
            $words[4],
            $words[5],
            $words[6],
            $words[8],
            $words[9],
            $words[10],
            $words[12],
            $words[13],
            $words[14],
            $words[15],
        ];
        $this->assertEquals([], array_diff($expected_texts, $texts));
    }
}
