<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 22.10.2015
 * Time: 11:37
 */

namespace Aot\Tools;


class ConvertTextIntoSlova
{
    /**
     * @param string $text
     * @return \Aot\Tools\SentenceContainingVariantsSlov[]
     */
    public static function convert($text)
    {
        assert(is_string($text));
        $parser = \Aot\Text\TextParser\TextParser::create();
        $parser->execute($text);
        $parser->render();
        $sentence_words = $parser->getSentenceWords();
        /** @var \Aot\Tools\SentenceContainingVariantsSlov[] $sentences */
        $sentences = [];
        foreach ($sentence_words as $sentence) {
            $tmp_sentence = \Aot\Tools\SentenceContainingVariantsSlov::create();
            foreach ($sentence as $word) {
                $slova = (\Aot\RussianMorphology\Factory::getSlova([$word]));
                if (count($slova)===0)
                {
                    $tmp_sentence->add($word, [$slova]);
                    continue;
                }
                $tmp_sentence->add($word, $slova);
            }
            $sentences[] = $tmp_sentence;
        }
        return $sentences;
    }
}