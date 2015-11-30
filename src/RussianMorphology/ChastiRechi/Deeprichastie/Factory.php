<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 18:15
 */

namespace Aot\RussianMorphology\ChastiRechi\Deeprichastie;

use Aot\MivarTextSemantic\OldAotConstants;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Neperehodnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Perehodnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Null as NullPerehodnost;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Nesovershennyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Sovershennyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Null as NullVid;

use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Nevozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Vozvratnyj;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Null as NullVozvratnost;


use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\FactoryBase
{

    /**
     * @param \DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base []
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];
        if (intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PARTICIPLE_CLASS_ID) {

            # вид
            $vid = $this->getVid($dw->parameters);

            # переходность
            $perehodnost = $this->getPerehodnost($dw->parameters);

            # возвратность
            $vozvratnost = $this->getVozvratnost($dw->parameters);

            foreach ($vid as $val_vid) {
                foreach ($perehodnost as $val_perehodnost) {
                    foreach ($vozvratnost as $val_vozvratnost) {
                        $words[] = $word = Base::create(
                            $text,
                            $val_vid,
                            $val_perehodnost,
                            $val_vozvratnost
                        );

                        $word->setInitialForm($dw->initial_form);

                    }
                }
            }
        }
        return $words;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vid\Base[]
     */
    private function getVid($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID])) {
            return [NullVid::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::VIEW_ID];
        $vid = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === \Aot\MivarTextSemantic\Constants::VIEW_PERFECTIVE_ID) {
                $vid[] = Sovershennyj::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::VIEW_IMPERFECT_ID) {
                $vid[] = Nesovershennyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vid;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Perehodnost\Base[]
     */
    private function getPerehodnost($parameters)
    {
        if (empty($parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID])) {
            return [NullPerehodnost::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::TRANSIVITY_ID];
        $perehodnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::TRANSITIVE()) {
                $perehodnost[] = Perehodnyj::create();
            } elseif (intval($val) === OldAotConstants::INTRANSITIVE()) {
                $perehodnost[] = Neperehodnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $perehodnost;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology\Vozvratnost\Base[]
     */
    private function getVozvratnost($parameters)
    {
        if (empty($parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()])) {
            return [NullVozvratnost::create()];
        }

        $param = $parameters[OldAotConstants::RETRIEVABLE_IRRETRIEVABLE()];

        $vozvratnost = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::RETRIEVABLE()) {
                $vozvratnost[] = Vozvratnyj::create();
            } elseif (intval($val) === OldAotConstants::IRRETRIEVABLE()) {
                $vozvratnost[] = Nevozvratnyj::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $vozvratnost;
    }

}