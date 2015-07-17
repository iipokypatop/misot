<?php

namespace Aot\RussianMorphology\ChastiRechi\Prichastie;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Null as NullChislo;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Kratkaya;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Polnaya;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Null as NullForma;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Perehodnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Neperehodnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Null as NullPerehodnost;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Sovershennyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Nesovershennyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Null as NullVid;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Vozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Nevozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Null as NullVozvratnost;


use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Buduschee;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Proshedshee;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Nastoyaschee;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Null as NullVremya;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Dejstvitelnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Stradatelnyj;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Null as NullRazryad;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Vinitelnij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Null as NullPadeszh;

use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Zhenskij;
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Null as NullRod;

use Aot\RussianMorphology\FactoryException;
use Dw;
use MorphAttribute;
use Word;


class Factory extends \Aot\RussianMorphology\Factory
{
    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Base[]
     * @throws \Exception
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;
        $words = [];

        if (isset($word->word) && $dw->id_word_class === COMMUNION_CLASS_ID) {
            # число
            if (!empty($dw->parameters->{NUMBER_ID})) {
                $chislo = $this->getChislo($dw->parameters->{NUMBER_ID});
                # род (зависит от наличия единственного числа)
                foreach ($chislo as $val_chislo) {
                    if (($val_chislo instanceof Edinstvennoe)) {
                        if (!empty($dw->parameters->{GENUS_ID})) {
                            $rod = $this->getRod($dw->parameters->{GENUS_ID});
                        } else {
                            throw new FactoryException("rod not defined", 24);
                        }
                    } else {
                        $rod = NullRod::create();
                    }
                }
            } else {
                throw new FactoryException("chislo not defined", 24);
            }

            # переходность
            if (!empty($dw->parameters->{TRANSIVITY_ID})) {
                $perehodnost = $this->getPerehodnost($dw->parameters->{TRANSIVITY_ID});
            } else {
                $perehodnost[] = Neperehodnij::create();
            }

            # падеж
            if (!empty($dw->parameters->{CASE_ID})) {
                $padeszh = $this->getPadeszh($dw->parameters->{CASE_ID});
            } else {
                throw new FactoryException("padeszh not defined", 24);
            }
            # вид
            if (!empty($dw->parameters->{VIEW_ID})) {
                $vid = $this->getVid($dw->parameters->{VIEW_ID});
            } else {
                throw new FactoryException("vid not defined", 24);
            }

            # возвратность
            if (!empty($dw->parameters->{\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()})) {
                $vozvratnost = $this->getVozvratnost($dw->parameters->{\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()});
            } else {
                $vozvratnost[] = Nevozvratnyj::create();
            }

            # время
            if (!empty($dw->parameters->{TIME_ID})) {
                $vremya = $this->getVremya($dw->parameters->{TIME_ID});
            } else {
                throw new FactoryException("vremya not defined", 24);
            }

            # разряд
            if (!empty($dw->parameters->{DISCHARGE_COMMUNION_ID})) {
                $razryad = $this->getRazryad($dw->parameters->{DISCHARGE_COMMUNION_ID});
                # форма (зависит от разряда)
                foreach ($razryad as $val_razryad) {
                    if (($val_razryad instanceof Stradatelnyj)) {
                        if (!empty($dw->parameters->{\OldAotConstants::WORD_FORM()})) {
                            $forma = $this->getForma($dw->parameters->{\OldAotConstants::WORD_FORM()});
                        } else {
                            $forma[] = Polnaya::create();
                        }
                    } else {
                        $forma[] = Polnaya::create();
                    }
                }
            } else {
                throw new FactoryException("razryad not defined", 24);
            }

            foreach ($forma as $val_forma) {
                foreach ($rod as $val_rod) {
                    foreach ($perehodnost as $val_perehodnost) {
                        foreach ($chislo as $val_chislo) {
                            foreach ($padeszh as $val_padeszh) {
                                foreach ($vid as $val_vid) {
                                    foreach ($vozvratnost as $val_vozvratnost) {
                                        foreach ($vremya as $val_vremya) {
                                            foreach ($razryad as $val_razryad) {
                                                $words[] = Base::create(
                                                    $text,
                                                    $val_chislo,
                                                    $val_forma,
                                                    $val_padeszh,
                                                    $val_perehodnost,
                                                    $val_rod,
                                                    $val_vid,
                                                    $val_vozvratnost,
                                                    $val_vremya,
                                                    $val_razryad
                                                );
                                            }
                                        }
                                    }
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
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base []
     */
    private function getPadeszh($value)
    {
        $padeszh = [];

        foreach ($value->id_value_attr as $val) {
            if ($val === CASE_SUBJECTIVE_ID) {
                $padeszh[] = Imenitelnij::create();
            } elseif ($val === CASE_GENITIVE_ID) {
                $padeszh[] = Roditelnij::create();
            } elseif ($val === CASE_DATIVE_ID) {
                $padeszh[] = Datelnij::create();
            } elseif ($val === CASE_ACCUSATIVE_ID) {
                $padeszh[] = Vinitelnij::create();
            } elseif ($val === CASE_INSTRUMENTAL_ID) {
                $padeszh[] = Tvoritelnij::create();
            } elseif ($val === CASE_PREPOSITIONAL_ID) {
                $padeszh[] = Predlozshnij::create();
            } else {
                $padeszh[] = NullPadeszh::create();
            }
        }

        return $padeszh;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Base []
     */
    private function getForma($value)
    {

        $forma = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::SHORT_WORD_FORM()) {
                $forma[] = Kratkaya::create();
            } elseif ($val === \OldAotConstants::FULL_WORD_FORM()) {
                $forma[] = Polnaya::create();
            } else {
                $forma[] = NullForma::create();
            }
        }
        return $forma;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Base []
     */
    private function getRod($value)
    {

        $rod = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif ($val === GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif ($val === GENUS_FEMININE_ID) {
                $rod[] = Zhenskij::create();
            } else {
                $rod[] = NullRod::create();
            }
        }
        return $rod;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Base []
     */
    private function getChislo($value)
    {

        $chislo = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif ($val === NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                $chislo[] = NullChislo::create();
            }
        }

        return $chislo;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Base []
     */
    private function getPerehodnost($value)
    {

        $perehodnost = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::TRANSITIVE()) {
                $perehodnost[] = Perehodnij::create();
            } elseif ($val === \OldAotConstants::INTRANSITIVE()) {
                $perehodnost[] = Neperehodnij::create();
            } else {
                $perehodnost[] = NullPerehodnost::create();
            }
        }

        return $perehodnost;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Base []
     */
    private function getVid($value)
    {

        $vid = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === VIEW_PERFECTIVE_ID) {
                $vid[] = Sovershennyj::create();
            } elseif ($val === VIEW_IMPERFECT_ID) {
                $vid[] = Nesovershennyj::create();
            } else {
                $vid[] = NullVid::create();
            }
        }

        return $vid;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Base []
     */
    private function getVremya($value)
    {

        $vremya = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === TIME_SIMPLE_ID) {
                $vremya[] = Nastoyaschee::create();
            } elseif ($val === TIME_FUTURE_ID) {
                $vremya[] = Buduschee::create();
            } elseif ($val === TIME_PAST_ID) {
                $vremya[] = Proshedshee::create();
            } else {
                $vremya[] = NullVremya::create();
            }
        }

        return $vremya;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Base []
     */
    private function getVozvratnost($value)
    {

        $vozvratnost = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::RETRIEVABLE()) {
                $vozvratnost[] = Vozvratnyj::create();
            } elseif ($val === \OldAotConstants::IRRETRIEVABLE()) {
                $vozvratnost[] = Nevozvratnyj::create();
            } else {
                $vozvratnost[] = NullVozvratnost::create();
            }
        }

        return $vozvratnost;
    }

    /**
     * @param $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Base []
     */
    private function getRazryad($value)
    {

        $razryad = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === COMMUNION_VALID_ID) {
                $razryad[] = Dejstvitelnyj::create();
            } elseif ($val === COMMUNION_PASSIVE_ID) {
                $razryad[] = Stradatelnyj::create();
            } else {
                $razryad[] = NullRazryad::create();
            }
        }

        return $razryad;
    }
}