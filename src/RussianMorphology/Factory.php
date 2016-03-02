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

    const REGULAR_FOR_WHITE_LIST = '/[A-Za-zА-Яа-яёЁ\\d]+/u';

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

    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public static function getSlova(array $words)
    {
        if (empty($words)) {
            return [];
        }

        return FactoryFromEntity::get()->getNonUniqueWords($words);
    }

 
    public static function getSlovaOld(array $words)
    {
        throw new \Exception('disabled');
        if (empty($words)) {
            return [];
        }

        return static::getPredictions($words);
    }


    /**
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public static function getPredictions(array $words)
    {
        if (empty($words)) {
            return [];
        }

        list($simple_words, $composite_words)
            = \Aot\RussianMorphology\CompositeWordProcessor::splitArrayWords($words);

        $factory_list = ChastiRechiRegistry::getFactories();

        $slova = [];

        $wdw = [];

        // точки из пространства с текущим индексом являются простыми словами
        if (!empty($simple_words)) {
            foreach ($simple_words as $index => $simple_word) {
                $points = self::factorySimpleWords([$simple_word]);
                $wdw[$index] = !empty($points[0]) ? $points[0] : [];
            }
        }

        // точки из пространства с текущим индексом являются сложными словами
        if (!empty($composite_words)) {
            foreach ($composite_words as $index => $composite_word) {
                $wdw[$index] = self::factoryCompositeWords($composite_word);
            }
        }

        ksort($wdw);
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
     * @param $simple_words
     * @return \PointWdw[][]
     */
    protected static function factorySimpleWords(array $simple_words)
    {
        return static::getDriver()->createWdwSpace($simple_words);
    }

    /**
     * @return \Aot\RussianMorphology\WdwDriver
     */
    protected static function getDriver()
    {
        return WdwDriver::create();
    }

    /**
     * Получение списка альтернатив по каждому отдельному элементу композитного слова (пример: "хозяин-мастер")
     * @param string $composite_word
     * @return \PointWdw[][]
     */
    protected static function factoryCompositeWords($composite_word)
    {
        assert(is_string($composite_word));


        $splitted = \Aot\RussianMorphology\CompositeWordProcessor::splitWord($composite_word);

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
                $main_slovo->dw->initial_form =
                    \Aot\RussianMorphology\CompositeWordProcessor::get()
                        ->joinParts($main_initial_form, $depend_initial_form);

            }
        }

        return $wdw_splitted[0];
    }

    protected function __clone()
    {
    }
}
