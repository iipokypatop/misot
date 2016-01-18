<?php

namespace Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe;

use Aot\MivarTextSemantic\OldAotConstants;
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


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 16:33
 */
class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \DictionaryWord $dw
     * @return static
     * @throws \Exception
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];
        if (intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID) {
            # одушевленность
            $odushevlyonnost = $this->getOdushevlennost($dw->parameters);

            # род
            $rod = $this->getRod($dw->parameters);

            # число
            $chislo = $this->getChislo($dw->parameters);

            # склонение
            $sklonenie = $this->getSklonenie($dw->parameters);

            # падеж
            $padeszh = $this->getPadeszh($dw->parameters);

            # нарицательность
            $naritcatelnost = $this->getNaritcatelnost($dw->parameters);

            # отглагольность
            $otglagolnost = $this->getOtglagolnost($dw->parameters);

            foreach ($chislo as $val_chislo) {
                foreach ($naritcatelnost as $val_naritcatelnost) {
                    foreach ($odushevlyonnost as $val_odushevlyonnost) {
                        foreach ($padeszh as $val_padeszh) {
                            foreach ($rod as $val_rod) {
                                foreach ($sklonenie as $val_sklonenie) {
                                    foreach ($otglagolnost as $val_otglagolnost) {
                                        $words[] = $word = Base::create(
                                            $text,
                                            $val_chislo,
                                            $val_naritcatelnost,
                                            $val_odushevlyonnost,
                                            $val_padeszh,
                                            $val_rod,
                                            $val_sklonenie,
                                            $val_otglagolnost
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
        return $words;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Base[]
     */
    protected function getOdushevlennost($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::ANIMALITY_ID])) {
            return [Morphology\Odushevlyonnost\Null::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::ANIMALITY_ID];

        $odushevlyonnost = [];

        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::ANIMALITY_ANIMATE_ID) {
                $odushevlyonnost[] = Odushevlyonnoe::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::ANIMALITY_INANIMATE_ID) {
                $odushevlyonnost[] = Neodushevlyonnoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $odushevlyonnost;

    }


    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base[]
     */
    protected function getRod($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID])) {
            return [Morphology\Rod\Null::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::GENUS_ID];

        $rod = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_MASCULINE_ID) {
                $rod[] = Muzhskoi::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_NEUTER_ID) {
                $rod[] = Srednij::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::GENUS_FEMININE_ID) {
                $rod[] = Zhenskii::create();
            } elseif ($val === '') {
                $rod[] = Morphology\Rod\Null::create();
            } else {

                //throw new \RuntimeException('Unsupported value exception = ' . var_export($parameters, 1));
            }
        }
        return $rod;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base
     */
    protected function getChislo($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::NUMBER_ID])) {
            return [Morphology\Chislo\Null::create()];
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
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Base[]
     */
    protected function getSklonenie($parameters)
    {

        if (empty($parameters[OldAotConstants::DECLENSION])) {
            return [Morphology\Sklonenie\Null::create()];
        }

        $param = $parameters[OldAotConstants::DECLENSION];
        $sklonenie = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::DECLENSION_1) {
                $sklonenie[] = Pervoe::create();
            } elseif (intval($val) === OldAotConstants::DECLENSION_2) {
                $sklonenie[] = Vtoroe::create();
            } elseif (intval($val) === OldAotConstants::DECLENSION_3) {
                $sklonenie[] = Tretie::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $sklonenie;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base[]
     */
    protected function getPadeszh($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::CASE_ID])) {
            return [Morphology\Padeszh\Null::create()];
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
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\Base []
     */
    protected function getNaritcatelnost($parameters)
    {
        if (empty($parameters[OldAotConstants::SELF_NOMINAL])) {
            return [Morphology\Naritcatelnost\Null::create()];
        }

        $param = $parameters[OldAotConstants::SELF_NOMINAL];
        $naritcatelnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::NOMINAL()) {
                $naritcatelnost[] = ImiaNaritcatelnoe::create();
            } elseif (intval($val) === OldAotConstants::SELF()) {
                $naritcatelnost[] = ImiaSobstvennoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $naritcatelnost;
    }

    /**
     * @param \Aot\MivarTextSemantic\MorphAttribute[] $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Base[]
     */
    protected function getOtglagolnost(array $parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::OTGLAGOLNOST_ID])) {
            return [Morphology\Otglagolnost\Null::create()];
        }

        foreach ($parameters as $parameter) {
            assert($parameter instanceof \Aot\MivarTextSemantic\MorphAttribute);
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::OTGLAGOLNOST_ID];
        $otglagolnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::OTGLAGOLNOE) {
                $otglagolnost[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Otglagolnoe::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::NEOTGLAGOLNOE) {
                $otglagolnost[] = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Otglagolnost\Neotglagolnoe::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }
        return $otglagolnost;
    }
}

