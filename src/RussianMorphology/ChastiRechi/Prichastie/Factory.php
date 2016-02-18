<?php

namespace Aot\RussianMorphology\ChastiRechi\Prichastie;

use Aot\MivarTextSemantic\OldAotConstants;
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

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;


class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Base[]
     * @throws \Exception
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::COMMUNION_CLASS_ID) {
            # число
            $chislo = $this->getChislo($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

            # переходность
            $perehodnost = $this->getPerehodnost($dw->parameters);

            # падеж
            $padeszh = $this->getPadeszh($dw->parameters);

            # вид
            $vid = $this->getVid($dw->parameters);

            # возвратность
            $vozvratnost = $this->getVozvratnost($dw->parameters);

            # время
            $vremya = $this->getVremya($dw->parameters);

            # разряд
            $razryad = $this->getRazryad($dw->parameters);

            # форма
            $forma = $this->getForma($dw->parameters);

            foreach ($forma as $val_forma) {
                foreach ($rod as $val_rod) {
                    foreach ($perehodnost as $val_perehodnost) {
                        foreach ($chislo as $val_chislo) {
                            foreach ($padeszh as $val_padeszh) {
                                foreach ($vid as $val_vid) {
                                    foreach ($vozvratnost as $val_vozvratnost) {
                                        foreach ($vremya as $val_vremya) {
                                            foreach ($razryad as $val_razryad) {
                                                $words[] = $word = Base::create(
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
        return $words;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base[]
     */
    private function getPadeszh($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::CASE_ID])) {
            return [NullPadeszh::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::CASE_ID];

        $padeszh = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID) {
                $padeszh[] = Imenitelnij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_GENITIVE_ID) {
                $padeszh[] = Roditelnij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_DATIVE_ID) {
                $padeszh[] = Datelnij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_ACCUSATIVE_ID) {
                $padeszh[] = Vinitelnij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_INSTRUMENTAL_ID) {
                $padeszh[] = Tvoritelnij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::CASE_PREPOSITIONAL_ID) {
                $padeszh[] = Predlozshnij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $padeszh;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Base[]
     */
    private function getForma($parameters)
    {

        if (empty($parameters[OldAotConstants::WORD_FORM()])) {
            return [NullForma::create()];
        }

        $param = $parameters[OldAotConstants::WORD_FORM()];

        $forma = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === OldAotConstants::SHORT_WORD_FORM()) {
                $forma[] = Kratkaya::create();
            } elseif ((int)($val) === OldAotConstants::FULL_WORD_FORM()) {
                $forma[] = Polnaya::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $forma;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Rod\Base[]
     */
    private function getRod($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID])) {
            return [NullRod::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID];

        $rod = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::GENUS_FEMININE_ID) {
                $rod[] = Zhenskij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $rod;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Chislo\Base[]
     */
    private function getChislo($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID])) {
            return [NullChislo::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID];

        $chislo = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::NUMBER_SINGULAR_ID) {
                $chislo[] = Edinstvennoe::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID) {
                $chislo[] = Mnozhestvennoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $chislo;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Perehodnost\Base[]
     */
    private function getPerehodnost($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID])) {
            return [NullPerehodnost::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID];

        $perehodnost = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === OldAotConstants::TRANSITIVE()) {
                $perehodnost[] = Perehodnij::create();
            } elseif ((int)($val) === OldAotConstants::INTRANSITIVE()) {
                $perehodnost[] = Neperehodnij::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $perehodnost;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vid\Base[]
     */
    private function getVid($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID])) {
            return [NullVid::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID];

        $vid = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::VIEW_PERFECTIVE_ID) {
                $vid[] = Sovershennyj::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::VIEW_IMPERFECT_ID) {
                $vid[] = Nesovershennyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vid;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Base[]
     */
    private function getVremya($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::TIME_ID])) {
            return [NullVremya::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::TIME_ID];

        $vremya = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::TIME_SIMPLE_ID) {
                $vremya[] = Nastoyaschee::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::TIME_FUTURE_ID) {
                $vremya[] = Buduschee::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::TIME_PAST_ID) {
                $vremya[] = Proshedshee::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vremya;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vozvratnost\Base []
     */
    private function getVozvratnost($parameters)
    {

        if (empty($parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()])) {
            return [NullVozvratnost::create()];
        }

        $param = $parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()];
        $vozvratnost = [];
        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === OldAotConstants::RETRIEVABLE()) {
                $vozvratnost[] = Vozvratnyj::create();
            } elseif ((int)($val) === OldAotConstants::IRRETRIEVABLE()) {
                $vozvratnost[] = Nevozvratnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vozvratnost;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Base[]
     */
    private function getRazryad($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::DISCHARGE_COMMUNION_ID])) {
            return [NullRazryad::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::DISCHARGE_COMMUNION_ID];

        $razryad = [];

        foreach ($param->id_value_attr as $val) {
            if ((int)($val) === \Aot\MivarTextSemantic\Constants::COMMUNION_VALID_ID) {
                $razryad[] = Dejstvitelnyj::create();
            } elseif ((int)($val) === \Aot\MivarTextSemantic\Constants::COMMUNION_PASSIVE_ID) {
                $razryad[] = Stradatelnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $razryad;
    }
}