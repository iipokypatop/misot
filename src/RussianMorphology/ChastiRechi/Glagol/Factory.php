<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13/07/15
 * Time: 13:54
 */

namespace Aot\RussianMorphology\ChastiRechi\Glagol;


use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Null as NullChislo;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Dejstvitelnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Stradatelnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null as NullRazryad;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Nesovershennyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Null as NullVid;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Neperehodnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Null as NullPerehodnost;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Vozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Null as NullVozvratnost;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Vtoroe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Null as NullSpryazhenie;


use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Povelitelnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Yslovnoe;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Null as NullNaklonenie;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Buduschee;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Nastoyaschee;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Null as NullVremya;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Zhenskii;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null as NullRod;

use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Pervoe as PervoeLitso;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Vtoroe as VtoroeLitso;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie as TretieLitso;
use Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Null as NullLitso;

use Aot\RussianMorphology\FactoryException;
use Dw;
use Word;

class Factory extends \Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Base []
     * @throws \Exception
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];
        if (isset($word->word) && intval($dw->id_word_class) === VERB_CLASS_ID) {

            # вид
            if (!empty($dw->parameters[VIEW_ID])) {
                $vid = $this->getVid($dw->parameters[VIEW_ID]);
            } else {
                throw new FactoryException("vid not defined", 24);
            }

            # переходность
            if (!empty($dw->parameters[TRANSIVITY_ID])) {
                $perehodnost = $this->getPerehodnost($dw->parameters[TRANSIVITY_ID]);
            } else {
                $perehodnost[] = NullPerehodnost::create();
            }

            # возвратность
            if (!empty($dw->parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()])) {
                $vozvratnost = $this->getVozvratnost($dw->parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()]);
            } else {
                $vozvratnost[] = NullVozvratnost::create();
            }

            # разряд
            if (!empty($dw->parameters[DISCHARGE_COMMUNION_ID])) {
                $razryad = $this->getRazryad($dw->parameters[DISCHARGE_COMMUNION_ID]);
            } else {
                $razryad[] = NullRazryad::create();
            }

            # спряжение
            if (!empty($dw->parameters[\OldAotConstants::CONJUGATION()])) {
                $spryazhenie = $this->getSpryazhenie($dw->parameters[\OldAotConstants::CONJUGATION()]);
            } else {
                $spryazhenie[] = NullSpryazhenie::create();
            }

            # число
            if (!empty($dw->parameters[NUMBER_ID])) {
                $chislo = $this->getChislo($dw->parameters[NUMBER_ID]);
            } else {
                $chislo[] = NullChislo::create();
            }

            # наклонение
            if (!empty($dw->parameters[MOOD_ID])) {
                $naklonenie = $this->getNaklonenie($dw->parameters[MOOD_ID]);
            } else {
                $naklonenie[] = NullNaklonenie::create();
            }

            # время
            if (!empty($dw->parameters[TIME_ID])) {
                $vremya = $this->getVremya($dw->parameters[TIME_ID]);
            } else {
                $vremya[] = NullVremya::create();
            }

            # лицо
            if (!empty($dw->parameters[PERSON_ID])) {
                $litso = $this->getLitso($dw->parameters[PERSON_ID]);
            } else {
                $litso[] = NullLitso::create();
            }

            # род
            if (!empty($dw->parameters[GENUS_ID])) {
                $rod = $this->getRod($dw->parameters[GENUS_ID]);
            } else {
                $rod[] = NullRod::create();
            }

            foreach ($chislo as $val_chislo) {
                foreach ($litso as $val_litso) {
                    foreach ($naklonenie as $val_naklonenie) {
                        foreach ($perehodnost as $val_perehodnost) {
                            foreach ($rod as $val_rod) {
                                foreach ($spryazhenie as $val_spryazhenie) {
                                    foreach ($vid as $val_vid) {
                                        foreach ($vozvratnost as $val_vozvratnost) {
                                            foreach ($vremya as $val_vremya) {
                                                foreach ($razryad as $val_razryad) {
                                                    $words[] = Base::create(
                                                        $text,
                                                        $val_chislo,
                                                        $val_litso,
                                                        $val_naklonenie,
                                                        $val_perehodnost,
                                                        $val_rod,
                                                        $val_spryazhenie,
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
        }
        return $words;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Base []
     */
    private function getChislo($param)
    {
        $chislo = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif (intval($val) === NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                $chislo[] = NullChislo::create();
            }
        }

        return $chislo;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Base []
     */
    private function getVid($param)
    {
        $vid = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === VIEW_PERFECTIVE_ID) {
                $vid[] = Sovershennyj::create();
            } elseif (intval($val) === VIEW_IMPERFECT_ID) {
                $vid[] = Nesovershennyj::create();
            } else {
                $vid[] = NullVid::create();
            }
        }

        return $vid;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base []
     */
    private function getPerehodnost($param)
    {
        $perehodnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::TRANSITIVE()) {
                $perehodnost[] = Perehodnyj::create();
            } elseif (intval($val) === \OldAotConstants::INTRANSITIVE()) {
                $perehodnost[] = Neperehodnyj::create();
            } else {
                $perehodnost[] = NullPerehodnost::create();
            }
        }

        return $perehodnost;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Base []
     */
    private function getVozvratnost($param)
    {
        $vozvratnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::RETRIEVABLE()) {
                $vozvratnost[] = Vozvratnyj::create();
            } elseif (intval($val) === \OldAotConstants::IRRETRIEVABLE()) {
                $vozvratnost[] = Nevozvratnyj::create();
            } else {
                $vozvratnost[] = NullVozvratnost::create();
            }
        }

        return $vozvratnost;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Base []
     */
    private function getRazryad($param)
    {
        $razryad = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === COMMUNION_VALID_ID) {
                $razryad[] = Dejstvitelnyj::create();
            } elseif (intval($val) === COMMUNION_PASSIVE_ID) {
                $razryad[] = Stradatelnyj::create();
            } else {
                $razryad[] = NullRazryad::create();
            }
        }

        return $razryad;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Base []
     */
    private function getSpryazhenie($param)
    {
        $spryazhenie = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::CONJUGATION_1()) {
                $spryazhenie[] = Pervoe::create();
            } elseif (intval($val) === \OldAotConstants::CONJUGATION_2()) {
                $spryazhenie[] = Vtoroe::create();
            } else {
                $spryazhenie[] = NullSpryazhenie::create();
            }
        }

        return $spryazhenie;

    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Base []
     */
    private function getNaklonenie($param)
    {
        $naklonenie = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::MOOD_INDICATIVE()) {
                $naklonenie[] = Izyavitelnoe::create();
            } elseif (intval($val) === \OldAotConstants::MOOD_IMPERATIVE()) {
                $naklonenie[] = Povelitelnoe::create();
            } elseif (intval($val) === \OldAotConstants::MOOD_SUBJUNCTIVE()) {
                $naklonenie[] = Yslovnoe::create();
            } else {
                $naklonenie[] = NullNaklonenie::create();
            }
        }

        return $naklonenie;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Base []
     */
    private function getVremya($param)
    {
        $vremya = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === TIME_SIMPLE_ID) {
                $vremya[] = Nastoyaschee::create();
            } elseif (intval($val) === TIME_FUTURE_ID) {
                $vremya[] = Buduschee::create();
            } elseif (intval($val) === TIME_PAST_ID) {
                $vremya[] = Proshedshee::create();
            } else {
                $vremya[] = NullVremya::create();
            }
        }

        return $vremya;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Base []
     */
    private function getRod($param)
    {
        $rod = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif (intval($val) === GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif (intval($val) === GENUS_FEMININE_ID) {
                $rod[] = Zhenskii::create();
            } else {
                $rod[] = NullRod::create();
            }
        }
        return $rod;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Base []
     */
    private function getLitso($param)
    {
        $litso = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === PERSON_RIFST_ID) {
                $litso[] = PervoeLitso::create();
            } elseif (intval($val) === PERSON_SECOND_ID) {
                $litso[] = VtoroeLitso::create();
            } elseif (intval($val) === PERSON_THIRD_ID) {
                $litso[] = TretieLitso::create();
            } else {
                $litso[] = NullLitso::create();
            }
        }
        return $litso;
    }
}