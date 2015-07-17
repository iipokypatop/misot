<?php

namespace Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe;

use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaSobstvennoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Odushevlyonnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Pervoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Tretie;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Vtoroe;
use Dw;
use MorphAttribute;
use Word;


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 16:33
 */
class Factory extends \Aot\RussianMorphology\Factory
{
    /**
     * @param Dw $dw
     * @param Word $word
     * @return static
     * @throws \Exception
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;
        $words = [];

        // проверить что dw - существительное. если нет то эксептион

        /*
         *
        Morphology\Chislo\Base $chislo,
        Morphology\Naritcatelnost\Base $naritcatelnost,
        Morphology\Odushevlyonnost\Base $odushevlyonnost,
        Morphology\Padeszh\Base $padeszh,
        Morphology\Rod\Base $rod,
        Morphology\Sklonenie\Base $sklonenie
         */
        if (isset($word->word) && intval($dw->id_word_class) === NOUN_CLASS_ID) {
            foreach ($dw->parameters as $value) {
                /** @var $value MorphAttribute */
                # одушевленность
                if ($value->id_morph_attr === ANIMALITY_ID) {
                    $odushevlyonnost = $this->getOdushevlennost($value);
                } # род
                elseif ($value->id_morph_attr === GENUS_ID) {
                    $rod = $this->getRod($value);
                } # число
                elseif ($value->id_morph_attr === NUMBER_ID) {
                    $chislo = $this->getChislo($value);
                } # склонение
                elseif ($value->id_morph_attr === \OldAotConstants::DECLENSION) {
                    $sklonenie = $this->getSklonenie($value);
                } # падеж
                elseif ($value->id_morph_attr === CASE_ID) {
                    $padeszh = $this->getPadeszh($value);
                } # нарицательность
                elseif ($value->id_morph_attr === \OldAotConstants::SELF_NOMINAL) {
                    $naritcatelnost = $this->getNaritcatelnost($value);
                }
            }
            if (!isset($odushevlyonnost)) {
                //throw new \RuntimeException("odushevlyonnost not defined");
                $odushevlyonnost[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Null::create();
            }
            if (!isset($rod)) {
                $rod[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Null::create();
                //throw new \RuntimeException("rod not defined");
            }
            if (!isset($chislo)) {
                $chislo[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Null::create();
                //throw new \RuntimeException("chislo not defined");
            }
            if (!isset($sklonenie)) {
                //throw new \RuntimeException("sklonenie not defined");
                $sklonenie[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();
            }
            if (!isset($naritcatelnost)) {
//                throw new \RuntimeException("naritcatelnost not defined");
                $naritcatelnost[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Null::create();
            }
            if (!isset($padeszh)) {
                $padeszh[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Null::create();
                // throw new \RuntimeException("padeszh not defined");
            }


            foreach ($chislo as $val_chislo) {
                foreach ($naritcatelnost as $val_naritcatelnost) {
                    foreach ($odushevlyonnost as $val_odushevlyonnost) {
                        foreach ($padeszh as $val_padeszh) {
                            foreach ($rod as $val_rod) {
                                foreach ($sklonenie as $val_sklonenie) {

                                    $words[] = Base::create(
                                        $text,
                                        $val_chislo,
                                        $val_naritcatelnost,
                                        $val_odushevlyonnost,
                                        $val_padeszh,
                                        $val_rod,
                                        $val_sklonenie
                                    );
                                }
                            }
                        }
                    }
                }

            }
        }
        return $words;
    }

    protected function getAnalyser()
    {
        return new Analyser;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Base []
     */
    protected function getOdushevlennost(MorphAttribute $value)
    {
        $odushevlyonnost = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === ANIMALITY_ANIMATE_ID) {
                $odushevlyonnost[] = Odushevlyonnoe::create();
            } elseif (current($value->id_value_attr) === ANIMALITY_INANIMATE_ID) {
                $odushevlyonnost[] = Neodushevlyonnoe::create();
            } else {
                $odushevlyonnost[] = Morphology\Odushevlyonnost\Null::create();
            }
        }

        return $odushevlyonnost;

    }


    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base []
     */
    protected function getRod(MorphAttribute $value)
    {

        $rod = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif (intval($val) === GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif (intval($val) === GENUS_FEMININE_ID) {
                $rod[] = Zhenskii::create();
            } else {
                $rod[] = Morphology\Rod\Null::create();
            }
        }
        return $rod;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base
     */
    protected function getChislo(MorphAttribute $value)
    {

        $chislo = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif (intval($val) === NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                $chislo[] = Morphology\Chislo\Null::create();
            }
        }


        return $chislo;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Base
     */
    protected function getSklonenie(MorphAttribute $value)
    {

        $sklonenie = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::DECLENSION_1) {
                $sklonenie[] = Pervoe::create();
            } elseif (intval($val) === \OldAotConstants::DECLENSION_2) {
                $sklonenie[] = Vtoroe::create();
            } elseif (intval($val) === \OldAotConstants::DECLENSION_3) {
                $sklonenie[] = Tretie::create();
            } else {
                $sklonenie[] = Morphology\Sklonenie\Null::create();
            }
        }

        return $sklonenie;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base []
     */
    protected function getPadeszh(MorphAttribute $value)
    {

        $padeszh = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === CASE_SUBJECTIVE_ID) {
                $padeszh[] = Imenitelnij::create();
            } elseif (intval($val) === CASE_GENITIVE_ID) {
                $padeszh[] = Roditelnij::create();
            } elseif (intval($val) === CASE_DATIVE_ID) {
                $padeszh[] = Datelnij::create();
            } elseif (intval($val) === CASE_ACCUSATIVE_ID) {
                $padeszh[] = Vinitelnij::create();
            } elseif (intval($val) === CASE_INSTRUMENTAL_ID) {
                $padeszh[] = Tvoritelnij::create();
            } elseif (intval($val) === CASE_PREPOSITIONAL_ID) {
                $padeszh[] = Predlozshnij::create();
            } else {
                $padeszh[] = Morphology\Padeszh\Null::create();
            }
        }


        return $padeszh;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Base []
     */
    protected function getNaritcatelnost(MorphAttribute $value)
    {

        $naritcatelnost = [];
        foreach ($value->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::SELF) {
                $naritcatelnost = ImiaNaritcatelnoe::create();
            } elseif (intval($val) === \OldAotConstants::NOMINAL) {
                $naritcatelnost = ImiaSobstvennoe::create();
            } else $naritcatelnost = Morphology\Naritcatelnost\Null::create();
        }

        return $naritcatelnost;
    }
}

