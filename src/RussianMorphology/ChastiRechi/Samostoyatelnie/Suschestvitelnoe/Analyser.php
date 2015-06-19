<?php

namespace RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 18:44
 */
class Analyser extends \RussianMorphology\Analyser
{
    /**
     * @var $protocol AnalysisProtocol
     */
    protected $protocol;

    /**
     * @param $text
     * @return AnalysisProtocol
     */
    public function run($text)
    {
        parent::run($text);

        foreach ($this->protocol->similar_word_forms as $text) {

        }

        $words = \DMivarDictionary::get_words(

        );

        $this->analyzeChislo();
        $this->analyzeSklonenie();
        $this->analyzeRod();
        $this->analyzePadeszh();
        $this->analyzeOdushevlyonnost();
        $this->analyzeNaritcatelnost();

        return $this->protocol;
    }

    protected function analyzeChislo()
    {


        $this->protocol->chislo = [];
    }

    protected function analyzeSklonenie()
    {
        $this->protocol->sklonenie = [];
    }

    protected function analyzeRod()
    {
        $this->protocol->rod = [];
    }

    protected function analyzePadeszh()
    {
        $this->protocol->padeszh = [];
    }

    protected function analyzeOdushevlyonnost()
    {
        $this->protocol->odushevlyonnost = [];
    }

    protected function analyzeNaritcatelnost()
    {
        $this->protocol->naritcatelnost = [];
    }

    protected function getProtocolObject()
    {
        return new AnalysisProtocol;
    }
}