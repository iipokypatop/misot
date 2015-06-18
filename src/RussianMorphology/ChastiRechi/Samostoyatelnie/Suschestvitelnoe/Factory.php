<?php

namespace RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 16:33
 */
class Factory extends \RussianMorphology\Factory
{
    public function analyze($text)
    {
        /**
         * @var $protocol \RussianMorphology\ChastiRechi\Samostoyatelnie\Suschestvitelnoe\AnalysisProtocol
         */
        $protocol = parent::analyze($text);

        $protocol->similar_word_forms; // [тучка, точка, тачка]

        if ($protocol->spell_check_status === Analyser::SPELL_ANALYSIS_PASSED) {

        }

        if (count($protocol->similar_word_forms) !== 1) {

        }

        return null;
    }

    protected function getAnalyser()
    {
        return new Analyser;
    }
}

