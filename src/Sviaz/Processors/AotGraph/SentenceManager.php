<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 11/12/15
 * Time: 12:28
 */

namespace Aot\Sviaz\Processors\AotGraph;


class SentenceManager
{
    /** @var string */
    protected $sentence = '';

    /** @var string[] */
    protected $sentence_words = [];

    /** @var int[] */
    protected $map_aot_id_sentence_id = [];

    /** @var string[] */
    protected $punctuation = [',', '.', ';', ':']; // знаки пунктуации


    /**
     * @param string[] $sentence_words
     *
     * @return \Aot\Sviaz\Processors\AotGraph\SentenceManager
     */
    public static function create(array $sentence_words)
    {
        return new static($sentence_words);
    }

    /**
     * @param string[] $sentence_words
     */
    protected function __construct(array $sentence_words)
    {
        foreach ($sentence_words as $sentence_word) {
            assert(is_string($sentence_word));
        }

        $this->sentence_words = $sentence_words;
        $this->sentence = join(' ', $sentence_words);
        $this->map_aot_id_sentence_id = $this->createAotIdSentenceIdMap($sentence_words);
        $this->rebuildMapsForDifficultWords();
    }


    /**
     * Получение смещений точек из АОТа со словами предложения
     *
     * @param string[] $sentence_words
     *
     * @return int[]
     */
    protected function createAotIdSentenceIdMap(array $sentence_words)
    {
        $map = [];
        foreach ($sentence_words as $id => $word) {
            if (!in_array($word, $this->punctuation)) {
                $map[] = $id;
            }
        }
        return $map;
    }

    /**
     * @return string
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * Получение слова из массива слов по id из АОТа
     *
     * @param int $aot_id
     *
     * @return string
     */
    public function getSentenceWordByAotId($aot_id)
    {
        assert(is_int($aot_id));
        return $this->sentence_words[$this->getSentenceIdByAotId($aot_id)];
    }

    /**
     * Получение id слова из предложения по id из АОТа
     *
     * @param int $aot_id
     *
     * @return int
     */
    public function getSentenceIdByAotId($aot_id)
    {
        assert(is_int($aot_id));
        if (!isset($this->map_aot_id_sentence_id[$aot_id])) {
            throw new \LogicException("Unknown element id: " . $aot_id);
        }
        return $this->map_aot_id_sentence_id[$aot_id];
    }

    /**
     * Метод перестраивающий карту с учётом того, что есть составные слова
     */
    protected function rebuildMapsForDifficultWords()
    {
        $tmp_map_aot_id_sentence_id = [];
        $tmp_sentence_words = [];

        $count_tmp_map_aot_id_sentence_id = count($tmp_map_aot_id_sentence_id);
        foreach ($this->sentence_words as $index => $sentence_word) {
            $tmp_sentence_words[] = $sentence_word;
            $count_parts = $this->countPart($sentence_word);
            for ($i = 1; $i < $count_parts; $i++) {
                $tmp_sentence_words[] = null;
            }
        }

        $tmp_map_aot_id_sentence_id = [];
        $main_id = null;
        foreach ($tmp_sentence_words as $id => $word) {
            if (!in_array($word, $this->punctuation)) {
                if ($word !== null) {
                    $tmp_map_aot_id_sentence_id[] = $id;
                    $main_id = $id;
                } else {
                    $tmp_map_aot_id_sentence_id[] = $main_id;
                }
            }
        }


        $this->map_aot_id_sentence_id = $tmp_map_aot_id_sentence_id;
        $this->sentence_words = $tmp_sentence_words;
    }

    /**
     * Подсчёт количества частей
     *
     * @param string $word
     *
     * @return int
     */
    protected function countPart($word)
    {
        // Делить слово может не что иное как пробел
        return (preg_match_all('#\s+#ui', $word) + 1);
    }

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return bool
     */
    public function hasOffset(array $syntax_model)
    {
        foreach ($syntax_model as $point) {
            assert(is_a($point, \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel::class, true));
        }

        $positions = [];
        foreach ($syntax_model as $point) {
            $positions[$point->kw][] = $point;
        }


        return count($positions) !== count($this->sentence_words);
    }

}