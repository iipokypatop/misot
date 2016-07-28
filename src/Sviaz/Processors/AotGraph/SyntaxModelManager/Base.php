<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 28/07/16
 * Time: 15:19
 */

namespace Aot\Sviaz\Processors\AotGraph\SyntaxModelManager;


class Base
{
    /** @var \Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors\Base[] */
    protected $post_processors = [];

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $sentence
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    public function run($sentence)
    {
        assert(is_string($sentence));
        $syntax_model = $this->createSyntaxModel($sentence);
        $syntax_model = $this->runPostProcessors($syntax_model);
        return $syntax_model;

    }

    /**
     * @param string $sentence
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    protected function createSyntaxModel($sentence)
    {
        $mivar = \WrapperAot\ModelNew\Lib\DMivarText::create(['txt' => $sentence]);
        $mivar->syntaxModel();
        return $mivar->getSyntaxModel();
    }

    /**
     * @param \Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors\Base[] $post_processors
     */
    public function addPostProcessors(array $post_processors)
    {
        foreach ($post_processors as $post_processor) {
            assert(is_a($post_processor, \Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors\Base::class, true));
        }
        $this->post_processors = array_merge($this->post_processors, $post_processors);
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    protected function runPostProcessors(array $syntax_model)
    {
        foreach ($this->post_processors as $post_processor) {
            $syntax_model = $post_processor->run($syntax_model);
        }
        return $syntax_model;
    }

}