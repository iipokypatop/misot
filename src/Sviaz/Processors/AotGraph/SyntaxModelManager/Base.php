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
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $sentence
     */
    public function run($sentence)
    {
        assert(is_string($sentence));

        $mivar = \WrapperAot\ModelNew\Lib\DMivarText::create(['txt' => $sentence]);

        $mivar->syntaxModel();

        $syntax_model = $mivar->getSyntaxModel();

    }
}