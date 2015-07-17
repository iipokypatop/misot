<?php

namespace Aot\RussianMorphology;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Dw;
use Word;


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 17:42
 */
abstract class Factory
{
    protected static $uniqueInstances = null;

    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
    }

    public function create()
    {

    }

    /**
     * @param $text
     * @return AnalysisProtocol
     */
    public function analyze($text)
    {
        $analyser = $this->getAnalyser();

        $protocol = $analyser->run($text);

        return $protocol;
    }

    protected function getAnalyser()
    {
        return new Analyser();
    }

    protected function __clone()
    {
    }

    public static function getSlova(array $words)
    {
        $const = new \D_Constants();
        $const->defineConstants();
        $syntax_parser = new \SyntaxParserManager();
        $syntax_parser->reg_parser->parse_text(join(' ', $words));
        $syntax_parser->create_dictionary_word();

        /** @var \MivarSpaceWdw[] $spaces */
        $spaces = [];
        foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
            $spaces[] = $syntax_parser->create_sentence_space($sentence);
        }

        /** @var \PointWdw[][] $wdw */
        $wdw = $spaces[0]->get_space_kw();
        $factory_list = ChastiRechiRegistry::getFactories();

        $slova = [];
        foreach ($wdw as $index => $points) {
            $slova[$index] = [];
            foreach ($points as $point) {
                foreach ($factory_list as $factory) {
                    $slova[$index] = array_merge(
                        $slova[$index],
                        $factory->build($point->dw, $point->w)
                    );
                }
            }
        }

        return $slova;

    }

    abstract public function build(Dw $dw, Word $word);
}

class FactoryException extends \RuntimeException
{

}