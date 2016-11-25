<?php


namespace Aot\RussianMorphology\ChastiRechi\Sokr;


use Aot\Exception;

class Factory extends \Aot\RussianMorphology\FactoryBase
{

    /**
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\Slovo
     */
    public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        throw new Exception('Kek!');
        if ((int)($dw->id_word_class) === 999) {
            $words[] = $word = Base::create($text);
            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }
}