<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 11:10
 */

namespace Aot\RussianMorphology\ChastiRechi\Narechie;

use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniia\Null;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniia\Prevoshodnaia;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniia\Polozhitelnaia;
use Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniia\Sravnitelnaia;

use Dw;
use MorphAttribute;
use Word;

class Factory extends \Aot\RussianMorphology\Factory
{

    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Narechie\Base[]
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->initial_form;
        $words = [];
        if (isset($word->word) && $dw->id_word_class === ADVERB_CLASS_ID) {
            # степень сравнения
            if(!empty($dw->parameters->{DEGREE_COMPOSITION_ID})){
                $stepen_sravneniia = $this->getStepenSravneniia($dw->parameters->{DEGREE_COMPOSITION_ID});
            }
            else{
                $stepen_sravneniia[] = Null::create();
            }
            foreach ($stepen_sravneniia as $val_stepen_sravneniia) {
                $words[] = Base::create(
                    $text,
                    $val_stepen_sravneniia
                );
            }
        }
        return $words;
    }

    /**
     * @param $param
     * @return \Aot\RussianMorphology\ChastiRechi\Narechie\Morphology\StepenSravneniia\Base []
     */
    private function getStepenSravneniia($param)
    {
        $stepen_sravneniia = [];
        foreach ($param->id_value_attr as $val) {
            if ($val === \OldAotConstants::POSITIVE_DEGREE_COMPARISON())
            {
                $stepen_sravneniia[] = Polozhitelnaia::create();
            }
            elseif ($val === DEGREE_SUPERLATIVE_ID)
            {
                $stepen_sravneniia[] = Prevoshodnaia::create();
            }
            elseif ($val === \OldAotConstants::COMPARATIVE_DEGREE_COMPARISON())
            {
                $stepen_sravneniia[] = Sravnitelnaia::create();
            }
            else{
                $stepen_sravneniia[] = Null::create();
            }
        }

        return $stepen_sravneniia;
    }
}