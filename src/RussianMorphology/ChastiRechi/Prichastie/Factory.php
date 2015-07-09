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
use Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Zalog\Null as NullZalog;

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

use Dw;
use MorphAttribute;
use Word;


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

        if (isset($word->word) && $dw->id_word_class === PARTICIPLE_CLASS_ID) {
            foreach ($dw->parameters as $value) {
                /** @var $value MorphAttribute */
                # форма
                if ($value->id_morph_attr === \OldAotConstants::WORD_FORM()) {
                    $forma = $this->getForma($value);
                }
                # род
                elseif ($value->id_morph_attr === GENUS_ID) {
                    $rod = $this->getRod($value);
                }
                # число
                elseif ($value->id_morph_attr === NUMBER_ID) {
                    $chislo = $this->getChislo($value);
                }
                # переходность
                elseif ($value->id_morph_attr === TRANSIVITY_ID ) {
                    $perehodnost = $this->getPerehodnost($value);
                }
                # падеж
                elseif ($value->id_morph_attr === CASE_ID) {
                    $padeszh = $this->getPadeszh($value);
                }
                # вид
                elseif ($value->id_morph_attr === VIEW_ID) {
                    $vid = $this->getVid($value);
                }
                # возвратность
                elseif ($value->id_morph_attr === \OldAotConstants::RETRIEVABLE_IRRETRIEVABLE() ) {
                    $vozvratnost = $this->getVozvratnost($value);
                }
                # время
                elseif ($value->id_morph_attr === TIME_ID) {
                    $vremya = $this->getVremya($value);
                }
                # залог
                elseif ($value->id_morph_attr === \OldAotConstants::VOICE() ) {
                    $zalog = $this->getZalog($value);
                }
            }

            if(empty($forma)){
                throw new \RuntimeException("rod not defined");
            }
            if(empty($rod)){
                throw new \RuntimeException("rod not defined");
            }
            if(empty($perehodnost)){
                throw new \RuntimeException("perehodnost not defined");
            }
            if(empty($chislo)){
                throw new \RuntimeException("chislo not defined");
            }
            if(empty($padeszh)){
                throw new \RuntimeException("padeszh not defined");
            }
            if(empty($vid)){
                throw new \RuntimeException("vid not defined");
            }
            if(empty($vozvratnost)){
                throw new \RuntimeException("vozvratnost not defined");
            }
            if(empty($vremya)){
                throw new \RuntimeException("vremya not defined");
            }
            if(empty($zalog)){
                throw new \RuntimeException("zalog not defined");
            }


            return Base::create(
                $text,
                $forma,
                $rod,
                $perehodnost,
                $chislo,
                $padeszh,
                $vid,
                $vozvratnost,
                $vremya,
                $zalog
            );

        } else throw new \Exception('not implemented yet');
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Padeszh\Base[]
     */
    private function getPadeszh(MorphAttribute $value) {
        $padeszh = [];

        foreach ($value->id_value_attr as $val) {
            if ($val === CASE_SUBJECTIVE_ID) {
                $padeszh[] = new Imenitelnij();
            }
            elseif ($val === CASE_GENITIVE_ID) {
                $padeszh[] = new Roditelnij();
            }
            elseif ($val === CASE_DATIVE_ID) {
                $padeszh[] = new Datelnij();
            }
            elseif ($val === CASE_ACCUSATIVE_ID) {
                $padeszh[] = new Vinitelnij();
            }
            elseif ($val === CASE_INSTRUMENTAL_ID) {
                $padeszh[] = new Tvoritelnij();
            }
            elseif ($val === CASE_PREPOSITIONAL_ID) {
                $padeszh[] = new Predlozshnij();
            }
            else{
                $padeszh[] = new NullPadeszh();
            }
        }

        return $padeszh;
    }

    /**
     * @param MorphAttribute $value
     * @return \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Forma\Base[]
     */
    private function getForma(MorphAttribute $value) {

        $forma = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::SHORT_WORD_FORM()) {
                $forma[] = new Kratkaya();
            }
            elseif ($val === \OldAotConstants::FULL_WORD_FORM()) {
                $forma[] = new Polnaya();
            }
            else
            {
                $forma[] = new NullForma();
            }
        }
        return $forma;
    }

    private function getRod(MorphAttribute $value) {

        $rod = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === GENUS_MASCULINE_ID) {
                $rod[] = new Muzhskoi();
            }
            elseif ($val === GENUS_NEUTER_ID) {
                $rod[] = new Srednij();
            }
            elseif ($val === GENUS_FEMININE_ID) {
                $rod[] = new Zhenskij();
            }
            else
            {
                $rod[] = new NullRod();
            }
        }
        return $rod;
    }

    private function getChislo(MorphAttribute $value) {

        $chislo = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === NUMBER_SINGULAR_ID)
            {
                $chislo[] = new Edinstvennoe();
            }
            elseif ($val === NUMBER_PLURAL_ID)
            {
                $chislo[] = new Mnozhestvennoe();
            }
            else {
                $chislo[] = new NullChislo();
            }
        }

        return $chislo;
    }

    private function getPerehodnost(MorphAttribute $value) {

        $perehodnost = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::TRANSITIVE())
            {
                $perehodnost[] = Perehodnij::create();
            }
            elseif ($val === \OldAotConstants::INTRANSITIVE())
            {
                $perehodnost[] = Neperehodnij::create();
            }
            else {
                $perehodnost[] = NullPerehodnost::create();
            }
        }

        return $perehodnost;
    }

    private function getVid(MorphAttribute $value) {

        $vid = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === VIEW_PERFECTIVE_ID)
            {
                $vid[] = new Sovershennyj();
            }
            elseif ($val === VIEW_IMPERFECT_ID)
            {
                $vid[] = new Nesovershennyj();
            }
            else {
                $vid[] = new NullVid();
            }
        }

        return $vid;
    }

    private function getVremya(MorphAttribute $value) {

        $vremya = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === TIME_SIMPLE_ID)
            {
                $vremya[] = new Nastoyaschee();
            }
            elseif ($val === TIME_FUTURE_ID)
            {
                $vremya[] = new Buduschee();
            }
            elseif ($val === TIME_PAST_ID)
            {
                $vremya[] = new Proshedshee();
            }
            else {
                $vremya[] = new NullVremya();
            }
        }

        return $vremya;
    }

    private function getVozvratnost(MorphAttribute $value) {

        $vozvratnost = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::RETRIEVABLE())
            {
                $vozvratnost[] = new Vozvratnyj();
            }
            elseif ($val === \OldAotConstants::IRRETRIEVABLE())
            {
                $vozvratnost[] = new Nevozvratnyj();
            }
            else {
                $vozvratnost[] = new NullVozvratnost();
            }
        }

        return $vozvratnost;
    }


    private function getZalog(MorphAttribute $value) {

        $zalog = [];
        foreach ($value->id_value_attr as $val) {
            if ($val === \OldAotConstants::ACTIVE_VOICE())
            {
                $zalog[] = new Dejstvitelnyj();
            }
            elseif ($val === \OldAotConstants::PASSIVE_VOICE())
            {
                $zalog[] = new Stradatelnyj();
            }
            else {
                $zalog[] = new NullZalog();
            }
        }

        return $zalog;
    }
}