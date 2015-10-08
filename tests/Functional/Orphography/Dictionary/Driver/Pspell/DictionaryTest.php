<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 006, 06.10.2015
 * Time: 15:20
 */

namespace AotTest\Functional\Orphography\Dictionary\Driver\Pspell;


class DictionaryTest extends \MivarTest\Base
{
    public function testWeightCorrectWorking()
    {
        $dictionary = \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::createStd(

            \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::DICTIONARY_RU

        );

        $word1 = \Aot\Orphography\Word::create("словооо");

        $word2 = \Aot\Orphography\Word::create("словооооо");

        $result = $dictionary->weight($word1, $word2);

        $this->assertEquals(
            4,
            $result
        );
    }
}