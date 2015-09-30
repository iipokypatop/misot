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


# от инфинитива
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Nesovershennyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Sovershennyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vid\Null as NullVid;
//
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Neperehodnyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Perehodnyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Perehodnost\Null as NullPerehodnost;
//
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Nevozvratnyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Vozvratnyj;
//use Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology\Vozvratnost\Null as NullVozvratnost;
###############




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

class Factory extends \Aot\RussianMorphology\ChastiRechi\Infinitive\Factory //\Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Base[]
     * @throws \Exception
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];
        if (isset($word->word) && intval($dw->id_word_class) === VERB_CLASS_ID) {

            # вид
            $vid = $this->getVid($dw->parameters);

            # переходность
            $perehodnost = $this->getPerehodnost($dw->parameters);

            # возвратность
            $vozvratnost = $this->getVozvratnost($dw->parameters);

            # разряд
            $razryad = $this->getRazryad($dw->parameters);

            # спряжение
            $spryazhenie = $this->getSpryazhenie($dw->parameters);

            # число
            $chislo = $this->getChislo($dw->parameters);

            # наклонение
            $naklonenie = $this->getNaklonenie($dw->parameters);

            # время
            $vremya = $this->getVremya($dw->parameters);

            # лицо
            $litso = $this->getLitso($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

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

                                                    $words[] = $word = Base::create(
                                                        $text,
                                                        $val_chislo,
                                                        $val_litso,
                                                        $val_naklonenie,
                                                        $val_vid,
                                                        $val_perehodnost,
                                                        $val_vozvratnost,
                                                        $val_rod,
                                                        $val_spryazhenie,
                                                        $val_vremya,
                                                        $val_razryad
                                                    );

                                                    $word->setInitialForm($dw->initial_form);
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
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Base[]
     */
    protected function getChislo($parameters)
    {

        if (empty($parameters[NUMBER_ID])) {
            return [NullChislo::create()];
        }

        $param = $parameters[NUMBER_ID];
        $chislo = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif (intval($val) === NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $chislo;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Base[]
     */
    protected function getVid($parameters)
    {

        if (empty($parameters[VIEW_ID])) {
            return [NullVid::create()];
        }

        $param = $parameters[VIEW_ID];
        $vid = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === VIEW_PERFECTIVE_ID) {
                $vid[] = Sovershennyj::create();
            } elseif (intval($val) === VIEW_IMPERFECT_ID) {
                $vid[] = Nesovershennyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vid;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Base[]
     */
    protected function getPerehodnost($parameters)
    {

        if (empty($parameters[TRANSIVITY_ID])) {
            return [NullPerehodnost::create()];
        }

        $param = $parameters[TRANSIVITY_ID];

        $perehodnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::TRANSITIVE()) {
                $perehodnost[] = Perehodnyj::create();
            } elseif (intval($val) === \OldAotConstants::INTRANSITIVE()) {
                $perehodnost[] = Neperehodnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $perehodnost;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Base[]
     */
    protected function getVozvratnost($parameters)
    {

        if (empty($parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()])) {
            return [NullVozvratnost::create()];
        }

        $param = $parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()];
        $vozvratnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::RETRIEVABLE()) {
                $vozvratnost[] = Vozvratnyj::create();
            } elseif (intval($val) === \OldAotConstants::IRRETRIEVABLE()) {
                $vozvratnost[] = Nevozvratnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vozvratnost;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Base[]
     */
    protected function getRazryad($parameters)
    {

        if (empty($parameters[DISCHARGE_COMMUNION_ID])) {
            return [NullRazryad::create()];
        }

        $param = $parameters[DISCHARGE_COMMUNION_ID];
        $razryad = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === COMMUNION_VALID_ID) {
                $razryad[] = Dejstvitelnyj::create();
            } elseif (intval($val) === COMMUNION_PASSIVE_ID) {
                $razryad[] = Stradatelnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $razryad;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Base[]
     */
    protected function getSpryazhenie($parameters)
    {

        if (empty($parameters[\OldAotConstants::CONJUGATION()])) {
            return [NullSpryazhenie::create()];
        }

        $param = $parameters[\OldAotConstants::CONJUGATION()];
        $spryazhenie = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::CONJUGATION_1()) {
                $spryazhenie[] = Pervoe::create();
            } elseif (intval($val) === \OldAotConstants::CONJUGATION_2()) {
                $spryazhenie[] = Vtoroe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $spryazhenie;

    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Base[]
     */
    protected function getNaklonenie($parameters)
    {

        if (empty($parameters[MOOD_ID])) {
            return [NullNaklonenie::create()];
        }

        $param = $parameters[MOOD_ID];
        $naklonenie = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \OldAotConstants::MOOD_INDICATIVE()) {
                $naklonenie[] = Izyavitelnoe::create();
            } elseif (intval($val) === \OldAotConstants::MOOD_IMPERATIVE()) {
                $naklonenie[] = Povelitelnoe::create();
            } elseif (intval($val) === \OldAotConstants::MOOD_SUBJUNCTIVE()) {
                $naklonenie[] = Yslovnoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $naklonenie;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Base[]
     */
    protected function getVremya($parameters)
    {

        if (empty($parameters[TIME_ID])) {
            return [NullVremya::create()];
        }

        $param = $parameters[TIME_ID];
        $vremya = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === TIME_SIMPLE_ID) {
                $vremya[] = Nastoyaschee::create();
            } elseif (intval($val) === TIME_FUTURE_ID) {
                $vremya[] = Buduschee::create();
            } elseif (intval($val) === TIME_PAST_ID) {
                $vremya[] = Proshedshee::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vremya;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Base[]
     */
    protected function getRod($parameters)
    {

        if (empty($parameters[GENUS_ID])) {
            return [NullRod::create()];
        }

        $param = $parameters[GENUS_ID];

        $rod = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif (intval($val) === GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif (intval($val) === GENUS_FEMININE_ID) {
                $rod[] = Zhenskii::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }
        return $rod;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Base[]
     */
    protected function getLitso($parameters)
    {

        if (empty($parameters[PERSON_ID])) {
            return [NullLitso::create()];
        }

        $param = $parameters[PERSON_ID];
        $litso = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === PERSON_RIFST_ID) {
                $litso[] = PervoeLitso::create();
            } elseif (intval($val) === PERSON_SECOND_ID) {
                $litso[] = VtoroeLitso::create();
            } elseif (intval($val) === PERSON_THIRD_ID) {
                $litso[] = TretieLitso::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }
        return $litso;
    }
}