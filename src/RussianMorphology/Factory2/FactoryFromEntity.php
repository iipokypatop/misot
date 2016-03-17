<?php
/**
 * Created by PhpStorm.
 * User: peter-local
 * Date: 006, 06, 03, 2016
 * Time: 15:24
 */
namespace Aot\RussianMorphology\Factory2;

use Aot\RussianMorphology\Slovo;

class FactoryFromEntity
{
    protected static $uniqueInstances = null;
    /** @var array[][] */
    public $error_log = [];
    public $timer1 = 0;


    /** @var  \Aot\RussianMorphology\Factory2\WordProcessor */
    protected $word_processor;

    /**
     * FactoryBase constructor.
     */
    protected function __construct()
    {
        $this->word_processor = \Aot\RussianMorphology\Factory2\WordProcessor::create();
    }

    /**
     * @return \Aot\RussianMorphology\Factory2\FactoryFromEntity
     */
    public static function get()
    {
        if (empty(static::$uniqueInstances[static::class])) {
            static::$uniqueInstances[static::class] = new static;
        }

        return static::$uniqueInstances[static::class];
    }

    /**
     * @brief Метод для генерирования слов и пунктуации с сохранением их последовательности
     *
     * @param string[] $words
     * @param int $search_mode
     * @return \Aot\Unit hashmap
     */
    public function getSlovaWithPunctuation(array $words, $search_mode = Mode::SEARCH_MODE_DEFAULT)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        assert(is_int($search_mode));

        if (empty($words)) {
            return [];
        }


        list($trash_words, $good_words) = $this->word_processor->splitWordsAndTrash($words);

        $punctuation = $this->word_processor->processPunctuation($trash_words);

        $slova = $this->getSlova(array_unique($good_words), $search_mode);

        $result = [];
        foreach ($words as $index => $word) {

            if (isset($slova[$word])) {

                $result[$index] = $this->reClone($slova[$word]);

            } elseif (isset($punctuation[$word])) {

                $result[$index] = $this->reClone($punctuation[$word]);

            } else {

                $result[$index] = [];
            }
        }

        return $result;
    }

    /**
     * @param string[] $words
     * @param int $search_mode
     * @return Slovo[][]
     */
    public function getSlova(array $words, $search_mode = Mode::SEARCH_MODE_DEFAULT)
    {
        return $this->getSlovaInternal($words, $search_mode);
    }

    protected function getSlovaInternal(array $words, $search_mode = Mode::SEARCH_MODE_DEFAULT)
    {
        assert(is_int($search_mode));

        foreach ($words as $word) {
            assert(is_string($word));
        }

        if (empty($words)) {
            return [];
        }

        $this->word_processor->getMode()->setSearchMode($search_mode);
        $this->word_processor->assertNotDuplicates($words);
        $cache_store = $this->word_processor->getCurrentCache();
        
        list($trash_words, $good_words) = $this->word_processor->splitWordsAndTrash($words);
        list($cached_words, $new_words) = $this->word_processor->splitCachedAndNewWords($good_words);
        list($simple_words, $composite_words) = $this->word_processor->splitArrayWords($new_words);

        $cached = $this->word_processor->processCachedWords($cached_words);
        $simple = $this->word_processor->processSimpleWords($simple_words);
        $composite = $this->word_processor->processCompositeWords($composite_words);
        $trash = $this->word_processor->processTrash($trash_words);

        $result = []; 


        

        foreach ($words as $word) {

            if (isset($trash[$word])) {

                $result[$word] = $trash[$word];

            } elseif (isset($cached[$word])) {

                $result[$word] = $this->reClone($cached[$word]);

            } elseif (isset($simple[$word])) {

                $result[$word] = $simple[$word];

                $cache_store->cacheSlova($word, $simple[$word]);

            } elseif (isset($composite[$word])) {

                $result[$word] = $composite[$word];

                $cache_store->cacheSlova($word, $composite[$word]);

            } else {

                throw new \LogicException ("missed word  " . var_export($word, 1));
            }
        }

        if (!$this->word_processor->getMode()->isSearchModeNotUsePredictor()) {

            foreach ($result as $word_name => $words_array) {

                if ($result[$word_name] !== []) {
                    continue;
                }

                $slova = $this->predict($word_name);

                if (!empty($slova[0])) {
                    $result[$word_name] = $slova[0];
                    continue;
                }
            }
        }

        if ($this->word_processor->getMode()->isSearchModeAddNullWords()) {
            foreach ($result as $word_name => $words_array) {
                $result[$word_name] = $result[$word_name] ?: [\Aot\RussianMorphology\NullWord::create($word_name)];
            }
        }

        return $result;
    }

    /**
     * @param Slovo[] $words
     * @return Slovo[]
     */
    protected function reClone(array $words)
    {
        $res = [];
        foreach ($words as $word) {
            $res[] = $word->reClone();
        }
        return $res;
    }

    /**
     * @param string $word_name
     * @return Slovo[][]
     */
    protected function predict($word_name)
    {
        return \Aot\RussianMorphology\Factory::getPredictions([$word_name]);
    }

    /**
     * @param string[] $words
     * @param int $search_mode
     * @return  Slovo[][]
     */
    public function getNonUniqueWords(array $words, $search_mode = Mode::SEARCH_MODE_DEFAULT)
    {
        foreach ($words as $word) {
            assert(is_string($word));
        }

        $slova = $this->getSlova(array_unique($words), $search_mode);

        /** @var Slovo[][] $result */
        $result = [];
        foreach ($words as $word) {
            $found_words = [];

            if (!empty($slova[$word])) {
                foreach ($slova[$word] as $slovo) {
                    $found_words[] = $slovo->reClone();
                }
            }

            $result[] = $found_words;
        }


        return $result;
    }

    /**
     * @return WordProcessor
     */
    public function getWordProcessor()
    {
        return $this->word_processor;
    }
}