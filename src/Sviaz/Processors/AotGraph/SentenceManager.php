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

    /** @var string[]  */
    protected $punctuation = [',', '.', ';', ':']; // знаки пунктуации

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
    }


    /**
     * Получение смещений точек из АОТа со словами предложения
     * @param string[] $sentence_words
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
     * @param int $aot_id
     * @return string
     */
    public function getSentenceWordByAotId($aot_id)
    {
        assert(is_int($aot_id));
        return $this->sentence_words[$this->getSentenceIdByAotId($aot_id)];
    }

    /**
     * Получение id слова из предложения по id из АОТа
     * @param int $aot_id
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


}