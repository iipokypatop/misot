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
    const PART_REGULAR_FOR_COMPOSITE_WORDS = '[A-Za-zА-Яа-яёЁ\\d]+';
    const COMPOSITE_WORDS_DELIMITER = '[\\-]';
    const DELIMITER_FOR_SPLITTED_WORDS = ',';

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

        list($simple_words, $composite_words) = self::splitArrayWords($words);

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
     * Разбиение массива слов на простые и составные
     * @param string[] $words
     * @return string[][]
     */
    protected static function splitArrayWords(array $words)
    {
        if (empty($words)) {
            return [[], []];
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
     * @param string[] $words
     * @return \Aot\RussianMorphology\Slovo[][]
     */
    public static function getSlova2(array $words)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        if (empty($words)) {
            return [];
        }


        $counted_values = array_count_values($words);
        if (max($counted_values) > 1) {

            $duplicates = array_filter($counted_values, function ($a) {
                return $a > 1;
            });

            throw new DuplicateException("входной массив слов не должен содержать дубликаты " . var_export($duplicates, 1));
        }

        /** @var Slovo $slova */
        $slova = [];

        foreach ($words as $word) {
            $slova[$word] = [];
        }


        /** @var \TextPersistence\API\APIcurrent $api */
        $api = \TextPersistence\API\TextAPI::getAPI();

        /** @var \TextPersistence\Entities\TextEntities\Mword[] $mwords */
        $mwords = $api->findBy(
            \TextPersistence\Entities\TextEntities\Mword::class,
            [
                'word' => $words
            ]
        );


        $factory = \Aot\RussianMorphology\FactoryFromEntity::get();

        foreach ($mwords as $mword) {
            foreach ($mword->getForms() as $form) {
                $slova[$mword->getWord()][] = $factory->buildFromEntity($form);
            }
        }

        return $slova;
    }

    public function create()
    {
    }

    protected function __clone()
    {
    }
}

class FactoryException extends \LogicException
{

}

class DuplicateException extends FactoryException
{

}