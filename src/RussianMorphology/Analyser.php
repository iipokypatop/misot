<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 18:43
 */

namespace Aot\RussianMorphology;


class Analyser
{
    const SPELL_ANALYSIS_OK = 1;
    const SPELL_ANALYSIS_CAN_BE_REPAIRED = 2;
    const SPELL_ANALYSIS_REPAIR_SUGGESTS_MORE_THAN_ONE_OPTION = 3;
    const SPELL_ANALYSIS_FAIL = 4;

    public $text;

    protected $similar = [];

    /**
     * @var $protocol AnalysisProtocol
     */
    protected $protocol;


    public function run($text)
    {
        $this->text = $text;

        $this->protocol = $this->getProtocolObject();
        $this->protocol->text = $text;
        $this->analyzeSpelling();;
        $this->analyzeSimilar();

        return $this->protocol;
    }

    protected function getProtocolObject()
    {
        return new AnalysisProtocol();
    }

    protected function analyzeSpelling()
    {
        $this->protocol->spell_check_status = static::SPELL_ANALYSIS_OK;
    }

    protected function analyzeSimilar()
    {
        $this->protocol->similar_word_forms = [];
    }

    protected function analyzePartsOfWord()
    {
        throw new \Exception('not implemented yet');
    }

    protected function analyzeAccent()
    {
        throw new \Exception('not implemented yet');
    }
}
