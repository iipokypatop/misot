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

        $sentence_strings = $parser->getSentences();

        /** @var \Aot\Tools\SentenceContainingVariantsSlov[] $sentences */
        $sentences = [];

        $sentence_words_all = [];

        foreach ($sentence_words as $index => $sentence) {
            foreach ($sentence as $word) {
                $sentence_words_all[$word] = $word;
            }
        }

        /** @var \Aot\Unit[][] $slova */
        $slova = \Aot\RussianMorphology\FactoryFromEntity::get()->getSlovaWithPunctuation(array_unique($sentence_words_all));

        foreach ($sentence_words as $index => $sentence) {

            $tmp_sentence = \Aot\Tools\SentenceContainingVariantsSlov::create($sentence_strings[$index]);

            foreach ($sentence as $word) {

                $cloned_slova = [];
                foreach ($slova[$word] as $item) {
                    $cloned_slova[$word] = $item->reClone();
                }

                $tmp_sentence->add($word, [$cloned_slova]);
            }

            $sentences[] = $tmp_sentence;
        }
        return $sentences;
    }
}