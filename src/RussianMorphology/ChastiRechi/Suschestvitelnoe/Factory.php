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
     * @throws \Exception
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;
        $chislo = new Morphology\Chislo\Base();
        $rod = new Morphology\Rod\Base();
        $sklonenie = new Morphology\Sklonenie\Base();
        $naritcatelnost = new Morphology\Naritcatelnost\Base();
        $odushevlyonnost = new Morphology\Odushevlyonnost\Base();
        $padeszh = new Morphology\Padeszh\Base();

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
        if (isset($word->word) && $dw->id_word_class === NOUN_CLASS_ID) {
            foreach ($dw->parameters as $value) {
                /** @var $value MorphAttribute */
                # одушевленность
                if ($value->id_morph_attr === ANIMALITY_ID) {
                   $this->getOdushevlennost($value);
                }
                # род
                elseif ($value->id_morph_attr === GENUS_ID) {
                   $this->getRod($value);
                }
                # число
                elseif ($value->id_morph_attr === NUMBER_ID) {
                    $this->getChislo($value);
                }
                # склонение
                elseif ($value->id_morph_attr === \OldAotConstants::DECLENSION) {
                    $this->getSklonenie($value);
                }
                # падеж
                elseif ($value->id_morph_attr === CASE_ID ) {
                    $this->getPadeszh($value);
                }
                # нарицательность
                elseif ($value->id_morph_attr === \OldAotConstants::SELF_NOMINAL) {
                    $this->getNaritcatelnost($value);
                }
            }
            return Base::create(
                $text,
                $chislo,
                $naritcatelnost,
                $odushevlyonnost,
                $padeszh,
                $rod,
                $sklonenie
            );

        } else throw new \Exception('not implemented yet');
    }

    protected function getAnalyser()
    {
        return new Analyser;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getOdushevlennost(MorphAttribute $value)
    {
        if (current($value->id_value_attr) === ANIMALITY_ANIMATE_ID) {
            $odushevlyonnost = new Odushevlyonnoe();
        }
        elseif (current($value->id_value_attr) === ANIMALITY_INANIMATE_ID) {
            $odushevlyonnost = new Neodushevlyonnoe();
        }
        else $odushevlyonnost = new Morphology\Odushevlyonnost\Null;

        return $odushevlyonnost;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getRod(MorphAttribute $value) {

        if (current($value->id_value_attr) === GENUS_MASCULINE_ID) {
            $rod = new Muzhskoi();
        }
        elseif (current($value->id_value_attr) === GENUS_NEUTER_ID) {
            $rod = new Srednij();
        }
        elseif (current($value->id_value_attr) === GENUS_FEMININE_ID) {
            $rod = new Zhenskii();
        }
        else $rod = new Morphology\Rod\Null();

        return $rod;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getChislo(MorphAttribute $value) {
        if (current($value->id_value_attr) === NUMBER_SINGULAR_ID) {
            $chislo = new Edinstvennoe();
        }
        elseif (current($value->id_value_attr) === NUMBER_PLURAL_ID) {
            $chislo = new Mnozhestvennoe();
        }
        else $chislo = new Morphology\Chislo\Null();

        return $chislo;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getSklonenie(MorphAttribute $value) {
        if (current($value->id_value_attr) === \OldAotConstants::DECLENSION_1) {
            $sklonenie = new Pervoe();
        }
        elseif (current($value->id_value_attr) === \OldAotConstants::DECLENSION_2) {
            $sklonenie = new Vtoroe();
        }
        elseif (current($value->id_value_attr) === \OldAotConstants::DECLENSION_3) {
            $sklonenie = new Tretie();
        }
        else $sklonenie = new Morphology\Sklonenie\Null();

        return $sklonenie;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getPadeszh(MorphAttribute $value) {
        if (current($value->id_value_attr) === CASE_SUBJECTIVE_ID) {
            $padeszh = new Imenitelnij();
        }
        elseif (current($value->id_value_attr) === CASE_GENITIVE_ID) {
            $padeszh = new Roditelnij();
        }
        elseif (current($value->id_value_attr) === CASE_DATIVE_ID) {
            $padeszh = new Datelnij();
        }
        elseif (current($value->id_value_attr) === CASE_ACCUSATIVE_ID) {
            $padeszh = new Vinitelnij();
        }
        elseif (current($value->id_value_attr) === CASE_INSTRUMENTAL_ID) {
            $padeszh = new Tvoritelnij();
        }
        elseif (current($value->id_value_attr) === CASE_PREPOSITIONAL_ID) {
            $padeszh = new Predlozshnij();
        }
        else $padeszh = new Morphology\Padeszh\Null();

        return $padeszh;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base
     */
    protected function getNaritcatelnost(MorphAttribute $value) {
        if (current($value->id_value_attr) === \OldAotConstants::SELF) {
            $naritcatelnost = new ImiaNaritcatelnoe();
        }
        elseif (current($value->id_value_attr) === \OldAotConstants::NOMINAL) {
            $naritcatelnost = new ImiaSobstvennoe();
        }
        else $naritcatelnost = new Morphology\Naritcatelnost\Null();

        return $naritcatelnost;
    }
}

