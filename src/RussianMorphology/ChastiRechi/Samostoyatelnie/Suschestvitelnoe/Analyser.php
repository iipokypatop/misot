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
     * @param $text
     * @return AnalysisProtocol
     */
    public function run($text)
    {
        /**
         * @var $protocol AnalysisProtocol
         */
        $protocol = parent::run($text);

        $protocol->chislo = $this->analyzeChislo();
        $protocol->sklonenie = $this->analyzeSklonenie();
        $protocol->rod = $this->analyzeRod();
        $protocol->padeszh = $this->analyzePadeszh();
        $protocol->odushevlyonnost = $this->analyzeOdushevlyonnost();
        $protocol->naritcatelnost = $this->analyzeNaritcatelnost();

        return $protocol;
    }

    protected function analyzeChislo()
    {
        return [];
    }

    protected function analyzeSklonenie()
    {
        return [];
    }

    protected function analyzeRod()
    {
        return [];
    }

    protected function analyzePadeszh()
    {
        return [];
    }

    protected function analyzeOdushevlyonnost()
    {
        return [];
    }

    protected function analyzeNaritcatelnost()
    {
        return [];
    }

    protected function getProtocol()
    {
        return new AnalysisProtocol;
    }

}
