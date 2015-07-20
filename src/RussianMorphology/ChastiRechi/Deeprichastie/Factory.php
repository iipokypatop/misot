<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 18:15
 */

namespace Aot\RussianMorphology\ChastiRechi\Deeprichastie;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Perehodnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Null as NullPerehodnost;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Null as NullVid;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Nevozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Vozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null as NullVozvratnost;

use Aot\RussianMorphology\FactoryException;
use Dw;
use Word;

class Factory extends \Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base []
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];
        if (isset($word->word) && intval($dw->id_word_class) === PARTICIPLE_CLASS_ID) {
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
                $perehodnost[] = Neperehodnyj::create();
            }

            # возвратность
            if (!empty($dw->parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()])) {
                $vozvratnost = $this->getVozvratnost($dw->parameters[\OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()]);
            } else {
                $vozvratnost[] = NullVozvratnost::create();
            }
            foreach ($vid as $val_vid) {
                foreach ($perehodnost as $val_perehodnost) {
                    foreach ($vozvratnost as $val_vozvratnost) {
                        $words[] = Base::create(
                            $text,
                            $val_vid,
                            $val_perehodnost,
                            $val_vozvratnost
                        );
                    }
                }
            }
        }
        return $words;
    }

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

}