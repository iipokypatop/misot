<?php

namespace Aot\Orphographia;

class Base
{
    protected $dictionary;
    protected $pspell_link;

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $pspell_config = pspell_config_create("ru");
        pspell_config_mode($pspell_config, PSPELL_FAST);
        $this->pspell_link = pspell_new_config($pspell_config);
    }

    public function checkWord($word)
    {
        if (pspell_check($this->pspell_link, $word)) {
            return $word;
        } else {
            $variants = pspell_suggest($this->pspell_link, $word);
            return $this->applyLevenshtein($word, $variants);
        }
    }

    protected function applyLevenshtein($word, array $variants)
    {
        $shortest = -1;
        $closest = $word;
        foreach ($variants as $variant) {
            $lev = levenshtein($word, $variant, 1, 1, 1);
            //print_r($variant.' - '.$lev );
            if ($lev <= $shortest || $shortest < 0) {
                $closest = $variant;
                $shortest = $lev;
            }
        }
        return $closest;
    }

    public function checkWords($words)
    {
        $timestart = microtime();
        $result_words = [];
        foreach ($words as $word) {
            if (pspell_check($this->pspell_link, $word)) {
                $result_words[] = $word;
            } else {
                $variants = pspell_suggest($this->pspell_link, $word);
                $result_words[] = $this->applyLevenshtein($word, $variants);
            }
        }
        $timestop = microtime();
        $result_time = $timestop - $timestart;
        return ['time' => $result_time, 'words' => $result_words];
    }

    public function addWordInDictionary()
    {

    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    public function setDictionary($dictionary)
    {
        $this->dictionary = $dictionary;
    }
}