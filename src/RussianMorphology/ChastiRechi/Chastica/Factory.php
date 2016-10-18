<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 18:23
 */

namespace Aot\RussianMorphology\ChastiRechi\Chastica;

class Factory extends \Aot\RussianMorphology\FactoryBase
{
    /**
     * @param \WrapperAot\ModelNew\Convert\DictionaryWord $dw
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[]
     */
    public function build(\WrapperAot\ModelNew\Convert\DictionaryWord $dw)
    {
        $text = $dw->word_form;
        $words = [];

        if ((int)($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PARTICLE_CLASS_ID) {
            $words[] = $word = Base::create(
                $text

            );

            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }


}