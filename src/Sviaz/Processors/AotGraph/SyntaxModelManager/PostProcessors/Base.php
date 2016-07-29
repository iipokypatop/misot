<?php

namespace Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors;


abstract class Base
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    abstract public function run(array $syntax_model);
}