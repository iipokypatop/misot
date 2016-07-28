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
            if (!$this->isNumeralWordClassId($item->dw->id_word_class)
                && $this->isNumeralInitialForm($item->dw->initial_form)
            ) {
                $item->dw->id_word_class = \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID;
                $item->dw->name_word_class = \WrapperAot\ModelNew\Convert\Defines::NUMERAL_FULL;
            }
        }
        return $syntax_model;
    }

    /**
     * @param int $word_class_id
     * @return bool
     */
    protected function isNumeralWordClassId($word_class_id)
    {
        assert(is_int($word_class_id));
        return $word_class_id === \WrapperAot\ModelNew\Convert\Defines::NUMERAL_CLASS_ID;
    }

    /**
     * @param string $initial_form
     * @return bool
     */
    protected function isNumeralInitialForm($initial_form)
    {
        return is_numeric($initial_form);
    }
}