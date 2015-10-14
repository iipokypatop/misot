<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 18:23
 */

namespace Aot\RussianMorphology\ChastiRechi\Chastica;



use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\Word;

class Factory extends \Aot\RussianMorphology\Factory
{
    /**
     * @param Dw $dw
     * @param Word $word
     * @return \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[]
     */
    public function build(Dw $dw, Word $word)
    {
        $text = $dw->word_form;
        $words = [];

        if (isset($word->word) && intval($dw->id_word_class) === \Aot\MivarTextSemantic\Constants::PARTICLE_CLASS_ID) {
            $words[] = $word = Base::create(
                $text

            );

            $word->setInitialForm($dw->initial_form);
        }
        return $words;
    }


}