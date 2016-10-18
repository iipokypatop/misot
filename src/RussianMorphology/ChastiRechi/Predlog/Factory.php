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
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Predlog\Base[]
     */
    public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID) {
            $words[] = $word = \Aot\RussianMorphology\ChastiRechi\Predlog\Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }

}