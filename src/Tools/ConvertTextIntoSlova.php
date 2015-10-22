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
        /** @var \Aot\Tools\SentenceContainingVariantsSlov[] $registry */
        $registry = [];
        foreach ($sentence_words as $sentence) {
            $tmp_registry = \Aot\Tools\SentenceContainingVariantsSlov::create();
            foreach ($sentence as $word) {
                $slova = (\Aot\RussianMorphology\Factory::getSlova([$word]));
                if (count($slova)===0)
                {
                    continue;
                }
                $tmp_registry->add($word, $slova);
            }
            $registry[] = $tmp_registry;
        }
        return $registry;
    }
}