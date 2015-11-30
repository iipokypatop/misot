<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\RussianMorphology\ChastiRechi\Predlog;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Predlog\Base[]
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if (intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID) {
            $words[] = $word = \Aot\RussianMorphology\ChastiRechi\Predlog\Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }

}