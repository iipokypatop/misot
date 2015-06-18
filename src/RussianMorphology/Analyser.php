<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 18:43
 */

namespace RussianMorphology;


class Analyser
{
    const SPELL_ANALYSIS_PASSED = 1;
    const SPELL_ANALYSIS_FAILED = 2;

    public $text;

    protected $similar = [];


    public function run($text)
    {
        $this->text = $text;

        $protocol = $this->getProtocol();
        $protocol->text = $text;
        $protocol->spell_check_status = $this->analyzeSpelling();;
        $protocol->similar_word_forms = $this->analyzeSimilar();

        return $protocol;
    }

    protected function getProtocol()
    {
        return new AnalysisProtocol();
    }

    protected function analyzeSpelling()
    {

        return static::SPELL_ANALYSIS_PASSED;
    }

    protected function analyzeSimilar()
    {
        return [];
    }
}