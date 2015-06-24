<?php

namespace RussianMorphology\ChastiRechi\Suschestvitelnoe;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 16:33
 */
class Factory extends \RussianMorphology\Factory
{
    public function build($text)
    {
        /**
         * @var $protocol AnalysisProtocol
         */
        $protocol = $this->analyze($text);

        $protocol->similar_word_forms; // [тучка, точка, тачка]

        die("asd");

        if (
            $protocol->spell_check_status === Analyser::SPELL_ANALYSIS_OK
            && count($protocol->similar_word_forms) === 1
        ) {
            return Base::create(
                $protocol->text,
                $protocol->chislo[0],
                $protocol->naritcatelnost[0],
                $protocol->odushevlyonnost[0],
                $protocol->padeszh[0],
                $protocol->rod[0],
                $protocol->sklonenie[0]
            );
        }

        return null;
    }

    protected function getAnalyser()
    {
        return new Analyser;
    }
}

