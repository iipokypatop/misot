<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 */

namespace Aot\RussianMorphology\ChastiRechi\Pristavka;

class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Pristavka\Base[]
     */
    public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === 999) {
            $words[] = $word = Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }

}