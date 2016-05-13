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


        $parser2 = \Aot\Text\TextParserByTokenizer\TokenizerBasedParser::createDefaultConfig();
        $parser2->run($text);

        $sentence_words = $parser2->getSentenceWords();

        $sentence_strings = $parser2->getSentencesStrings();

        /** @var \Aot\Tools\SentenceContainingVariantsSlov[] $sentences */
        $sentences = [];

        $sentence_words_all = [];

        foreach ($sentence_words as $index => $sentence) {
            foreach ($sentence as $word) {
                $sentence_words_all[$word] = $word;
            }
        }

        /** @var \Aot\Unit[][] $slova */
        $slova = \Aot\RussianMorphology\Factory2\FactoryFromEntity::get()->getSlovaWithPunctuation(array_unique($sentence_words_all));

        foreach ($sentence_words as $index => $sentence) {

            $tmp_sentence = \Aot\Tools\SentenceContainingVariantsSlov::create($sentence_strings[$index]);

            foreach ($sentence as $word) {

                $cloned_slova = [];
                foreach ($slova[$word] as $item) {
                    $cloned_slova[] = $item->reClone();
                }

                $tmp_sentence->add($word, [$cloned_slova]);
            }

            $sentences[] = $tmp_sentence;
        }


        //Объединение объектов числительных в составное числительное, если это возможно
        static::mergeSimpleChislitelnie($sentences);

        return $sentences;
    }

    /**
     * @param \Aot\Tools\SentenceContainingVariantsSlov[] $sentences
     */
    protected static function mergeSimpleChislitelnie(array &$sentences)
    {
        /** @var \Aot\Tools\SentenceContainingVariantsSlov $sentence */
        foreach ($sentences as $sentence) {
            $sequences = static::findSequencesOfChislitelnie($sentence);
            foreach ($sequences as $sequence) {
                $elements_for_union = [];
                $parts_text_of_union = [];
                foreach ($sequence as $index => $elements) {
                    $sentence->removeByIndex($index);
                    $elements_for_union[] = $elements[0];
                    $parts_text_of_union [] = $elements[0]->getText();
                }
                $union_element = \Aot\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe\Base::createNew(join(' ',
                    $parts_text_of_union), $elements_for_union);
                $sentence->insertAfterIndex(array_keys($sequence)[0], [$union_element->getText()], [[$union_element]]);
            }
        }
    }

    /**
     * @param SentenceContainingVariantsSlov $sentence
     * @return \Aot\RussianMorphology\Slovo[][][]
     */
    protected static function findSequencesOfChislitelnie(\Aot\Tools\SentenceContainingVariantsSlov $sentence)
    {
        $sequences = [];
        $sequence = [];
        $tmp_sequence = [];
        foreach ($sentence->getSlova() as $index => $slova) {
            $flag = false;
            /** @var \Aot\RussianMorphology\Slovo $slovo */
            foreach ($slova as $slovo) {
                if ($slovo instanceof \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base) {
                    $flag = true;
                    $tmp_sequence[$index][] = $slovo;
                }
            }
            if ($flag) {
                $sequence = $sequence + $tmp_sequence;
                $tmp_sequence = [];
            } else {
                if (count($sequence) > 1) {
                    $sequences[] = $sequence;
                    $sequence = [];
                } else {
                    $sequence = [];
                }
            }
        }
        return $sequences;
    }
}