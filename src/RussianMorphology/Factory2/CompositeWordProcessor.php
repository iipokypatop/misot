<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 018, 18, 02, 2016
 * Time: 16:12
 */

namespace Aot\RussianMorphology\Factory2;


class CompositeWordProcessor
{
    const COMPOSITE_WORDS_DELIMITER = '[-]';
    const PART_REGULAR_FOR_COMPOSITE_WORDS = '[A-Za-zА-Яа-яёЁ0-9]+';
    const DELIMITER_FOR_SPLITTED_WORDS = ',';

    protected static $uniqueInstances = null;

    /**
     * FactoryBase constructor.
     */
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

    public static function splitWord($composite_word)
    {
        return preg_split("/" . self::COMPOSITE_WORDS_DELIMITER . "/u", $composite_word);
    }

    /**
     * Разбиение массива слов на простые и составные
     * @param string[] $words
     * @return string[][]
     */
    public static function splitArrayWords(array $words)
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
    public static function isCompositeWord($word)
    {
        assert(
            is_string($word)
            ||
            is_int($word)
        );

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
     * @param string $p1
     * @param string $p2
     * @return string
     */
    public static function joinParts($p1, $p2)
    {
        assert(is_string($p1));
        assert(is_string($p2));

        return
            $p1 . static::DELIMITER_FOR_SPLITTED_WORDS . $p2;
    }
}