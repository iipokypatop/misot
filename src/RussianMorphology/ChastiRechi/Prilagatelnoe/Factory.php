<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 13:55
 */

namespace Aot\RussianMorphology\ChastiRechi\Prilagatelnoe;

use Aot\MivarTextSemantic\OldAotConstants;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Null as NullChislo;

use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Kratkaya;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Null as NullForma;

use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Null as NullPadeszh;

use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Kachestvennoe;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Otnositelnoe;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Prityazhatelnoe;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null as NullRazryad;

use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Zhenskij;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null as NullRod;

use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Polozhitelnaya;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Prevoshodnaya;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Sravnitelnaya;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null as NullStepenSravneniia;

class Factory extends \Aot\RussianMorphology\Factory
{

    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if (intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::ADJECTIVE_CLASS_ID) {

            # число
            $chislo = $this->getChislo($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

            # разряд
            $razryad = $this->getRazryad($dw->parameters);

            # форма
            $forma = $this->getForma($dw->parameters);

            # степень сравнения
            $stepen_sravneniia = $this->getStepenSravneniia($dw->parameters);

            # падеж
            $padeszh = $this->getPadeszh($dw->parameters);

            foreach ($chislo as $val_chislo) {
                foreach ($rod as $val_rod) {
                    foreach ($razryad as $val_razryad) {
                        foreach ($forma as $val_forma) {
                            foreach ($stepen_sravneniia as $val_stepen_sravneniia) {
                                foreach ($padeszh as $val_padeszh) {
                                    $words[] = $word = Base::create(
                                        $text,
                                        $val_chislo,
                                        $val_forma,
                                        $val_padeszh,
                                        $val_razryad,
                                        $val_rod,
                                        $val_stepen_sravneniia
                                    );

                                    $word->setInitialForm($dw->initial_form);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $words;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Base[]
     */
    private function getChislo($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID])) {
            return [NullChislo::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID];
        $chislo = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $chislo;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Base[]
     */
    private function getRod($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID])) {
            return [NullRod::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID];
        $rod = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_FEMININE_ID) {
                $rod[] = Zhenskij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }
        return $rod;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Base[]
     */
    private function getRazryad($parameters)
    {
        if (empty($parameters[OldAotConstants::RANK_ADJECTIVES()])) {
            return [NullRazryad::create()];
        }

        $param = $parameters[OldAotConstants::RANK_ADJECTIVES()];

        $razryad = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::QUALIFYING_ADJECTIVE()) {
                $razryad[] = Kachestvennoe::create();
            } elseif (intval($val) === OldAotConstants::RELATIVE_ADJECTIVE()) {
                $razryad[] = Otnositelnoe::create();
            } elseif (intval($val) === OldAotConstants::POSSESSIVE_ADJECTIVE()) {
                $razryad[] = Prityazhatelnoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $razryad;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Base[]
     */
    private function getForma($parameters)
    {

        if (empty($parameters[OldAotConstants::WORD_FORM()])) {
            return [NullForma::create()];
        }

        $param = $parameters[OldAotConstants::WORD_FORM()];

        $forma = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::SHORT_WORD_FORM()) {
                $forma[] = Kratkaya::create();
            } elseif (intval($val) === OldAotConstants::FULL_WORD_FORM()) {
                $forma[] = Polnaya::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $forma;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Base[]
     */
    private function getStepenSravneniia($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::DEGREE_COMPOSITION_ID])) {
            return [NullStepenSravneniia::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::DEGREE_COMPOSITION_ID];

        $stepen_sravneniia = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::POSITIVE_DEGREE_COMPARISON()) {
                $stepen_sravneniia[] = Polozhitelnaya::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::DEGREE_SUPERLATIVE_ID) {
                $stepen_sravneniia[] = Prevoshodnaya::create();
            } elseif (intval($val) === OldAotConstants::COMPARATIVE_DEGREE_COMPARISON()) {
                $stepen_sravneniia[] = Sravnitelnaya::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $stepen_sravneniia;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base[]
     */
    private function getPadeszh($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::CASE_ID])) {
            return [NullPadeszh::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::CASE_ID];

        $padeszh = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID) {
                $padeszh[] = Imenitelnij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_GENITIVE_ID) {
                $padeszh[] = Roditelnij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_DATIVE_ID) {
                $padeszh[] = Datelnij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_ACCUSATIVE_ID) {
                $padeszh[] = Vinitelnij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_INSTRUMENTAL_ID) {
                $padeszh[] = Tvoritelnij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::CASE_PREPOSITIONAL_ID) {
                $padeszh[] = Predlozshnij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $padeszh;
    }
}