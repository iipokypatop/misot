<?php

namespace Aot\RussianMorphology\ChastiRechi\Fraz;


class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Fraz\Base[]
     */
    public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === 15) {
            $words[] = $word = \Aot\RussianMorphology\ChastiRechi\Fraz\Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }
}