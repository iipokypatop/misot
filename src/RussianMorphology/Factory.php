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
class Factory
{
    const REGULAR_FOR_WHITE_LIST = '/[A-Za-zА-Яа-яёЁ]+/u';
    const PART_REGULAR_FOR_COMPOSITE_WORDS = '[A-Za-zА-Яа-яёЁ]+';
    const COMPOSITE_WORDS_DELIMITER = '[\\-]';
    const DELIMITER_FOR_SPLITTED_WORDS = ',';


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

        if( empty($words)){
            return [];
        }

        list($simple_words, $composite_words) = self::splitArrayWords($words);

        $factory_list = ChastiRechiRegistry::getFactories();

        $slova = [];

        $wdw = [];

        // точки из пространства с текущим индексом являются простыми словами
        if (!empty($simple_words)) {
            $wdw = self::factorySimpleWords($simple_words);
        }

        // точки из пространства с текущим индексом являются сложными словами
        if (!empty($composite_words)) {
            foreach ($composite_words as $index => $composite_word) {
                $wdw[$index] = self::factoryCompositeWords($composite_word);
            }
        }

        foreach ($wdw as $index => $points) {
            $slova[$index] = [];
            foreach ($points as $point) {
                foreach ($factory_list as $factory) {
                    $slova[$index] = array_merge(
                        $slova[$index],
                        $factory->build($point->dw)
                    );
                }
            }
        }


        return $slova;

    }


    /**
     * Разбиение массива слов на простые и составные
     * @param string[] $words
     * @return string[][]
     */
    protected static function splitArrayWords(array $words)
    {

        if (empty($words)) {
            return [];
        }

        $simple_words = []; // простые слова
        $composite_words = []; // составные слова

        foreach ($words as $index => $word) {

            // ловим сложные слова
            if (self::isCompositeWord($word)) {
                $composite_words[$index] = $word;
            } else {
                $simple_words[$index] = $word;
            }
        }

        return [$simple_words, $composite_words];
    }


    /**
     * Проверка на композитное слово
     * @param string $word
     * @return bool
     */
    protected static function isCompositeWord($word)
    {
        assert(is_string($word));

        return preg_match(self::getCompositeRegular(), $word);
    }

    /**
     * Регулярка для поиска составных слов
     * @return string
     */
    protected static function getCompositeRegular()
    {
        return '/^'
        . self::PART_REGULAR_FOR_COMPOSITE_WORDS
        . self::COMPOSITE_WORDS_DELIMITER
        . self::PART_REGULAR_FOR_COMPOSITE_WORDS
        . '$/u';
    }

    /**
     * @param $simple_words
     * @return \PointWdw[][]
     */
    protected static function factorySimpleWords(array $simple_words)
    {
        return WdwDriver::createWdwSpace($simple_words);
    }

    /**
     * Получение списка альтернатив по каждому отдельному элементу композитного слова (пример: "хозяин-мастер")
     * @param $composite_word
     * @return \PointWdw[][]
     */
    protected static function factoryCompositeWords($composite_word)
    {

        assert(is_string($composite_word));

        $splitted = self::splitWord($composite_word);

        if (!isset($splitted[0], $splitted[1])) {
            throw new \LogicException("Wrong composite word: " . $composite_word);
        }

        $wdw_splitted = self::factorySimpleWords($splitted);


        // главное слово - первое слово
        foreach ($wdw_splitted[0] as $id_main => $main_slovo) {

            $main_initial_form = $main_slovo->dw->initial_form;

            // зависимое слово - второе слово
            $cache_initial_form = [];

            foreach ($wdw_splitted[1] as $depend_slovo) {
                $depend_initial_form = $depend_slovo->dw->initial_form;
                // поскольку морфология зависимого слова не учитывается, то берём только вариации начальных форм
                if (in_array($depend_initial_form, $cache_initial_form, true)) {
                    continue;
                }
                $cache_initial_form[] = $depend_initial_form;
                $main_slovo->dw->word_form = $composite_word;
                $main_slovo->dw->initial_form = $main_initial_form . self::DELIMITER_FOR_SPLITTED_WORDS . $depend_initial_form;
            }
        }

        return $wdw_splitted[0];
    }

    protected static function splitWord($composite_word)
    {
        return preg_split("/" . self::COMPOSITE_WORDS_DELIMITER . "/u", $composite_word);
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


}

class FactoryException extends \RuntimeException
{

}