<?php

namespace Aot\Orphography;

class Base1
{
    protected $dictionary;
    protected $pspell_link;
    protected $statistic = ['count' => 0, 'true' => 0, 'false' => 0];


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
            $lev = levenshtein($word, $variant, 1, 10, 1);
            //print_r($variant.' - '.$lev );
            if ($lev < $shortest || $shortest < 0) {
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
        $this->setCount(count($words));
        foreach ($words as $word) {
            if (pspell_check($this->pspell_link, $word)) {
                $result_words[] = $word;
                $this->addTrue();
            } else {
                $variants = pspell_suggest($this->pspell_link, $word);
                $result_words[] = $this->applyLevenshtein($word, $variants);
                $this->addFalse();
            }
        }
        $timestop = microtime();
        $result_time = $timestop - $timestart;
        return ['time' => $result_time, 'statistic' => $this->getStatistic(), 'words' => $result_words];
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

    /**
     * @return array
     */
    public function getStatistic()
    {
        return $this->statistic;
    }

    public function clearStatistic()
    {
        $this->statistic = ['count' => 0, 'true' => 0, 'false' => 0];;
    }

    public function setCount($count)
    {
        $this->statistic['count'] = $count;
    }

    public function addTrue()
    {
        $this->statistic['true'] = $this->statistic['true'] + 1;
    }

    public function addFalse()
    {
        $this->statistic['false'] = $this->statistic['false'] + 1;
    }


}