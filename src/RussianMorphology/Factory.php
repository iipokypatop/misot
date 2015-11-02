<?php

namespace Aot\RussianMorphology;

use Aot\MivarTextSemantic\Dw;
use Aot\MivarTextSemantic\MivarSpaceWdw;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxParserManager;
use Aot\MivarTextSemantic\Word;
use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;


/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 18.06.2015
 * Time: 17:42
 */
abstract class Factory
{
    const REGULAR_FOR_WHITE_LIST = '/[A-Za-zА-Яа-яёЁ]+/u';

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


    protected function __clone()
    {
    }

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public static function getSlova(array $words)
    {

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


    /**
     * @brief Метод для генерирования слов и пунктуации с сохранением их последовательности
     *
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]|\Aot\RussianSyntacsis\Punctuaciya\Zapiataya[][]
     */
    public static function getSlovaWithPunctuation(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }
        $result = [];
        foreach ($words as $index => $word) {
            if (preg_match(static::REGULAR_FOR_WHITE_LIST, $word)) {
                $result[$index] = static::getSlova([$word])[0];
            } else {
                $result[$index] = [\Aot\RussianSyntacsis\Punctuaciya\Factory::getInstance()->build($word)];
            }
        }

        return $result;
    }


    abstract public function build(Dw $dw, Word $word);
}

class FactoryException extends \RuntimeException
{

}