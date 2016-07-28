<?php
namespace Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors;

class ChangeWordClassForPointsWithNumericWord extends Base
{

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    public function run(array $syntax_model)
    {
        foreach ($syntax_model as $item) {
            if ($item->dw->id_word_class !== \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID
                && is_numeric($item->dw->initial_form)
            ) {
                $item->dw->id_word_class = \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID;
                $item->dw->name_word_class = \WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL;
//                print_r($item);
//                die();
            }
        }
        return $syntax_model;
    }
}