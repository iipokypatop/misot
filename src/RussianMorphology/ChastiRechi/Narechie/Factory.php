<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 11:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Narechie;

use Aot\MivarTextSemantic\OldAotConstants;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Null;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Prevoshodnaya;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Polozhitelnaya;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Sravnitelnaya;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Narechie\Base[]
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];
        if (isset($word->word) && intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::ADVERB_CLASS_ID) {

            # степень сравнения
            $stepen_sravneniia = $this->getStepenSravneniia($dw->parameters);

            foreach ($stepen_sravneniia as $val_stepen_sravneniia) {
                $words[] = $word = Base::create(
                    $text,
                    $val_stepen_sravneniia
                );

                $word->setInitialForm($dw->initial_form);
            }
        }
        return $words;
    }

    /**
     * @param array $parameters
     * @return \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniya\Base[]
     */
    private function getStepenSravneniia($parameters)
    {

        if (empty($parameters[\Aot\MivarTextSemantic\Constants::DEGREE_COMPOSITION_ID])) {
            return [Null::create()];
        }

        $param = $parameters[\Aot\MivarTextSemantic\Constants::DEGREE_COMPOSITION_ID];
        $stepen_sravneniia = [];
        foreach ($param->id_value_attr as $val) {
            if (intval($val) === OldAotConstants::POSITIVE_DEGREE_COMPARISON()) {
                $stepen_sravneniia[] = Polozhitelnaya::create();
            } elseif (intval($val) === \Aot\MivarTextSemantic\Constants::DEGREE_SUPERLATIVE_ID) {
                $stepen_sravneniia[] = Prevoshodnaya::create();
            } elseif (intval($val) === OldAotConstants::COMPARATIVE_DEGREE_COMPARISON()) {
                $stepen_sravneniia[] = Sravnitelnaya::create();
            } else {
                throw new \RuntimeException('Unsupported value exception = ' . var_export($val, 1));
            }
        }

        return $stepen_sravneniia;
    }
}