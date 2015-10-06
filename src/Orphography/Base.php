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
    /**
     * @param string $text Обычный текст
     */
    public function run($text)
    {
        assert(is_string($text));
        $texts_of_words = $this->parseStr($text);

        $words = [];
        foreach ($texts_of_words as $text_of_word) {
            $words[] = $this->builder($text_of_word);
        }

        $this->execute($words);

        return $this->getWords();
    }

    /**
     * @param string $text Текст (много слов)
     * @return array
     */
    protected function parseStr($text)
    {
        assert(is_string($text));
        //todo предобработка или соглашение о "не буквах"
        //todo заменить preg_match_all
        $texts_of_words = explode(" ", $text);
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
     */
    protected function execute($words)
    {

        foreach ($words as $word) {
            $word->getText();
            //вызов сбора совпадений
        }
    }
}