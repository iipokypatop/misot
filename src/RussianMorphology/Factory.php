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
    const PART_REGULAR_FOR_COMPOSITE_WORDS = '[A-Za-zА-Яа-яёЁ]+';
    const CHARACTERS_FOR_SPLIT_COMPOSITE_WORDS = '[\\-]';
    const DELIMITER_FOR_SPLITTED_WORDS = ',';

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

    /**
     * Получение списка альтернатив по каждому отдельному элементу композитного слова (хозяин-мастер)
     * @param string[] $composite_words
     * @return array
     */
    protected static function getCompositeWords(array $composite_words)
    {
        if (empty($composite_words)) {
            return [];
        }


        $variants = []; // все варианты сложного слова

        foreach ($composite_words as $id_compose => $compose_word) {

            $splitted = preg_split("/".self::CHARACTERS_FOR_SPLIT_COMPOSITE_WORDS."/u", $compose_word);

            $splitted_slova = self::getSlova($splitted);

            // главное слово - первое слово
            foreach ($splitted_slova[0] as $id_main => $main_slovo) {

                $main_initial_form = $main_slovo->getInitialForm();
                // зависимое слово - второе слово
                $cache_nf_dep = [];

                foreach ($splitted_slova[1] as $dep_slovo) {
                    $dep_initial_form = $dep_slovo->getInitialForm();
                    // поскольку морфология зависимого слова не учитывается, то берём только вариации начальных форм
                    if (in_array($dep_initial_form, $cache_nf_dep)) {
                        continue;
                    }
                    $cache_nf_dep[] = $dep_initial_form;
                    $main_slovo->setText($compose_word);
                    $main_slovo->setInitialForm($main_initial_form . self::DELIMITER_FOR_SPLITTED_WORDS . $dep_initial_form);
                    $variants[$id_compose][$id_main] = $main_slovo;
                }
            }

        }

        return $variants;
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
        $composite_words = [];
        foreach ($wdw as $index => $points) {
            $slova[$index] = [];
            foreach ($points as $point) {
                // ловим сложные слова
//                if (preg_match(self::REGULAR_FOR_COMPOSE_WORDS, $point->w->word)) {
                if (preg_match(
                    '/'
                    . self::PART_REGULAR_FOR_COMPOSITE_WORDS
                    . self::CHARACTERS_FOR_SPLIT_COMPOSITE_WORDS
                    . self::PART_REGULAR_FOR_COMPOSITE_WORDS
                    . '/u',
                    $point->w->word)
                ) {
                    $composite_words[$index] = $point->w->word;
                    break;
                }

                foreach ($factory_list as $factory) {
                    $slova[$index] = array_merge(
                        $slova[$index],
                        $factory->build($point->dw)
                    );
                }
            }
        }


        if (!empty($composite_words)) {
            $composite = self::getCompositeWords($composite_words);
            // пополняем список слов композитными словами
            foreach ($composite as $index => $item) {
                $slova[$index] = $item;
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


    abstract public function build(\DictionaryWord $dw);
}

class FactoryException extends \RuntimeException
{

}