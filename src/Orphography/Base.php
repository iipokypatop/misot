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
     * @return Subtext[]
     */
    public function run($text)
    {
        $timestart = microtime();
        assert(is_string($text));
        $texts_of_subtexts = $this->parseStr($text);

        $subtexts = [];
        foreach ($texts_of_subtexts as $text_of_subtext) {
            $subtexts[] = $this->builder($text_of_subtext);
        }

        $dictionares[] = \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::createStd("ru");
        $dictionares[] = \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::createStd("en");

        $this->execute($subtexts, $dictionares);
        $timestop = microtime();

        //print_r("Затраченное время: " . ($timestop - $timestart) . "\n");
        return $subtexts;
    }

    /**
     * @param string $text Текст (обычный)
     * @return string[]
     */
    protected function parseStr($text)
    {
        assert(is_string($text));
        //todo поправить
        $texts_of_subtexts = preg_split('/[\s,?!\.:\"\"0-9]+/', $text);
        return $texts_of_subtexts;
    }

    /**
     * @param string $text Текст одного слова
     * @return \Aot\Orphography\Subtext
     */
    protected function builder($text)
    {
        assert(is_string($text));
        $subtext_obj = \Aot\Orphography\Subtext::create($text);
        return $subtext_obj;
    }


    /**
     * @param \Aot\Orphography\Subtext[] $subtexts
     * @param \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary[] $dictionaries
     */
    protected function execute(array $subtexts, array $dictionaries)
    {
        foreach ($subtexts as $subtext) {
            assert(is_a($subtext, \Aot\Orphography\Subtext::class), true);
        }

        foreach ($dictionaries as $dictionarie) {
            assert(is_a($dictionarie, \Aot\Orphography\Dictionary\Driver\Pspell\Dictionary::class), true);
        }

        foreach ($subtexts as $subtext) {
            foreach ($dictionaries as $dictionary) {
                if ($dictionary->check($subtext)) {
                    $matching = \Aot\Orphography\Matching::create($dictionary, 1);
                } else {
                    $matching = \Aot\Orphography\Matching::create($dictionary, 0);
                }
                /** @var \Aot\Orphography\Suggestion $suggestion_for_subtext */
                $suggestion_for_subtext = $dictionary->suggest($subtext);
                $subtext->addSuggestion($suggestion_for_subtext);
                $subtext->addMatching($matching);
            }
        }
    }
}