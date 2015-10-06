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
     * @param string $text
     */
    public function run($text)
    {
        assert(is_string($text));
        $texts_of_words = $this->parseStr($text);
    }

    /**
     * @param string $text Текст (много слов)
     * @return array
     */
    protected function parseStr($text)
    {
        assert(is_string($text));
        //todo предобработка или соглашение о "не буквах"
        $texts_of_words = explode(" ", $text);
        return $texts_of_words;
    }

    /**
     * @param string $text Текст одного слова
     * @return array
     */
    public function builder($text)
    {
        //создать объект слова
    }

    public function execute()
    {
        //заполнить объект слова
        return;
    }
}