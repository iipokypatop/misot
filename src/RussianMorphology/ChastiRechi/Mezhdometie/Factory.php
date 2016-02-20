<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\RussianMorphology\ChastiRechi\Mezhdometie;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Mezhdometie\Base[]
     */
    public function build(\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::INTERJECTION_CLASS_ID) {
            $words[] = $word = Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }

}