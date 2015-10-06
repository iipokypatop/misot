<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 06.10.2015
 * Time: 14:00
 */

namespace Aot\Orphography;


class Base
{

    protected function __construct()
    {
    }

    public static function create()
    {
        return new static();
    }


    /**
     * @param string $text Обычный текст
     * @return Word[]
     */
    public function run($text)
    {
        $timestart = microtime();
        assert(is_string($text));
        $texts_of_words = $this->parseStr($text);

        $words = [];
        foreach ($texts_of_words as $text_of_word) {
            $words[] = $this->builder($text_of_word);
        }

        $dictionares[] = \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::createStd("ru");
        $dictionares[] = \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::createStd("en");

        $this->execute($words, $dictionares);
        $timestop = microtime();

        print_r("Затраченное время: " . ($timestop - $timestart) . "\n");
        return $words;
    }

    /**
     * @param string $text Текст (много слов)
     * @return array
     */
    protected function parseStr($text)
    {
        assert(is_string($text));
        //todo поправить
        $texts_of_words = preg_split('/[\s,?!\.:\"\"0-9]+/', $text);
        //$texts_of_words = explode(" ", $text);
        return $texts_of_words;
    }

    /**
     * @param string $text Текст одного слова
     * @return array
     */
    protected function builder($text)
    {
        assert(is_string($text));
        $word_obj = \Aot\Orphography\Word::create($text);
        return $word_obj;
    }


    /**
     * @param \Aot\Orphography\Word[] $words
     * @param \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary[] $dictionaries
     */
    protected function execute(array $words, array $dictionaries)
    {
        foreach ($words as $word) {
            foreach ($dictionaries as $dictionary) {
                if ($dictionary->check($word)) {
                    $matching = \Aot\Orphography\Matching::create($dictionary, 1);
                } else {
                    $matching = \Aot\Orphography\Matching::create($dictionary, 0);
                }
                /** @var \Aot\Orphography\Suggestion $suggestion_for_word */
                $suggestion_for_word = $dictionary->suggest($word);
                $word->addSuggestion($suggestion_for_word);
                $word->addMatching($matching);
            }
        }
    }
}