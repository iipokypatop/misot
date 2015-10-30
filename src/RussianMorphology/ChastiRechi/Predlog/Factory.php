<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\RussianMorphology\ChastiRechi\Predlog;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\Factory
{
    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Predlog\Base[]
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];

        if (isset($word->word) && intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID) {
            $words[] = $word = \Aot\RussianMorphology\ChastiRechi\Predlog\Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }

}