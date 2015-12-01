<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 30/11/15
 * Time: 19:47
 */

namespace Aot\RussianMorphology;


use Aot\MivarTextSemantic\MivarSpaceWdw;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxParserManager;

class WdwDriver
{

    public static function create()
    {
        return new static();
    }
    /**
     * Создания пространства точек
     * @param string[] $words
     * @return \PointWdw[][]
     */
    public function createWdwSpace(array $words)
    {
        if (empty($words)) {
            return [];
        }

        $syntax_parser = new SyntaxParserManager();
        $syntax_parser->reg_parser->parse_text(join(' ', $words));
        $syntax_parser->create_dictionary_word();

        /** @var MivarSpaceWdw[] $spaces */
        $spaces = [];
        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
            $spaces[] = $syntax_parser->create_sentence_space($sentence);
        }

        if (empty($spaces[0])) {
            return [];
        }

        /** @var \PointWdw[][] $wdw */
        return $spaces[0]->get_space_kw();
    }
}