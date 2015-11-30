<?php
namespace Aot\RussianMorphology\ChastiRechi\Mestoimenie;


use Aot\MivarTextSemantic\OldAotConstants;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Edinstvennoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Mnozhestvennoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Null as NullChislo;

use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Pervoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Tretie;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Vtoroe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Null as NullLitso;

use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Datelnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Imenitelnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Predlozshnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Roditelnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Tvoritelnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Vinitelnij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Null as NullPadeszh;

use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Lichnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Neopredelennoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Opredelitelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otnositelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Otricatelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Prityazhatelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Ukazatelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Voprositelnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Vozvratnoe;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Null as NullRazryad;

use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Muzhskoi;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Srednij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Zhenskij;
use Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Null as NullRod;

class Factory extends \Aot\RussianMorphology\FactoryBase
{

    /**
     * @param \DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base[]
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];
        if (intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID) {

            # число
            $chislo = $this->getChislo($dw->parameters);

            # лицо
            $litso = $this->getLitso($dw->parameters);

            # падеж
            $padeszh = $this->getPadeszh($dw->parameters);

            # разряд
            $razryad = $this->getRazryad($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

            foreach ($chislo as $val_chislo) {
                foreach ($litso as $val_litso) {
                    foreach ($padeszh as $val_padeszh) {
                        foreach ($razryad as $val_razryad) {
                            foreach ($rod as $val_rod) {
                                $words[] = $word = Base::create(
                                    $text,
                                    $val_chislo,
                                    $val_litso,
                                    $val_padeszh,
                                    $val_razryad,
                                    $val_rod
                                );

                                $word->setInitialForm($dw->initial_form);
                            }
                        }
                    }
                }
            }
        }
        return $words;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Chislo\Base[]
     */
    protected function getChislo($parameters)
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
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Litso\Base[]
     */
    protected function getLitso($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID])) {
            return [NullLitso::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID];

        $litso = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::PERSON_RIFST_ID) {
                $litso[] = Pervoe::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::PERSON_SECOND_ID) {
                $litso[] = Vtoroe::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::PERSON_THIRD_ID) {
                $litso[] = Tretie::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $litso;

    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Padeszh\Base[]
     */
    protected function getPadeszh($parameters)
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

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Razryad\Base[]
     */
    protected function getRazryad($parameters)
    {
        if (empty($parameters[OldAotConstants::RANK_PRONOUNS()])) {
            return [NullRazryad::create()];
        }

        $param = $parameters[OldAotConstants::RANK_PRONOUNS()];

        $razryad = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::PERSONAL_PRONOUN()) {
                $razryad[] = Lichnoe::create();
            } elseif (intval($val) === OldAotConstants::REFLEXIVE_PRONOUN()) {
                $razryad[] = Vozvratnoe::create();
            } elseif (intval($val) === OldAotConstants::POSSESSIVE_PRONOUN()) {
                $razryad[] = Prityazhatelnoe::create();
            } elseif (intval($val) === OldAotConstants::NEGATIVE_PRONOUN()) {
                $razryad[] = Otricatelnoe::create();
            } elseif (intval($val) === OldAotConstants::INDEFINITE_PRONOUN()) {
                $razryad[] = Neopredelennoe::create();
            } elseif (intval($val) === OldAotConstants::INTERROGATIVE_PRONOUN()) {
                $razryad[] = Voprositelnoe::create();
            } elseif (intval($val) === OldAotConstants::RELATIVE_PRONOUN()) {
                $razryad[] = Otnositelnoe::create();
            } elseif (intval($val) === OldAotConstants::DEMONSTRATIVE_PRONOUN()) {
                $razryad[] = Ukazatelnoe::create();
            } elseif (intval($val) === OldAotConstants::ATTRIBUTIVE_PRONOUN()) {
                $razryad[] = Opredelitelnoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $razryad;
    }

    /**
     * @param $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Rod\Base[]
     */
    protected function getRod($parameters)
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
}