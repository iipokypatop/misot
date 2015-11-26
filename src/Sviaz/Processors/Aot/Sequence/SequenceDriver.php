<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 026, 26.11.2015
 * Time: 13:52
 */

namespace Aot\Sviaz\Processors\Aot\Sequence;




class SequenceDriver
{
    /**
     * @var \Aot\Sviaz\Processors\Aot\Sequence\SequenceToWordsConverter
     */
    protected $seq_words;

    /** @var \Aot\Sviaz\Processors\Aot\Builder */
    protected $builder;

    /**
     * @param \Aot\Sviaz\Sequence $sequence
     */
    public function __construct(\Aot\Sviaz\Sequence $sequence)
    {
        $this->builder = \Aot\Sviaz\Processors\Aot\Builder::create();

        $this->seq_words = SequenceToWordsConverter::create($sequence);

        $sentence_words_array = $this->seq_words->getSentenceWordsArray();

        $this->aot_launcher = \Aot\Sviaz\Processors\Aot\LegacyDriver::create(
            $this->joinWordsToSentence($sentence_words_array)
        );
    }

    /**
     * @param string[] $sentence_words_array
     * @return string
     */
    protected function joinWordsToSentence(array $sentence_words_array)
    {
        foreach ($sentence_words_array as $item) {
            assert(is_string($item));
        }

        $delimiter = ' ';

        return join($delimiter, $sentence_words_array);
    }

    public static function create(\Aot\Sviaz\Sequence $sequence)
    {
        $ob = new static($sequence);

        return $ob;
    }

    /**
     * @return string[]
     */
    public function getWordsArray()
    {
        return $this->seq_words->getSentenceWordsArray();
    }

    public function isModelEmpty()
    {
        return $this->aot_launcher->isModelEmpty();
    }

    public function getSyntaxModel()
    {
        return $this->aot_launcher->getSyntaxModel();
    }

    public function getOffsetManager()
    {
        return $this->seq_words->getOffsetManager();
    }

    /**
     * @return \Sentence_space_SP_Rel[]
     */
    public function getLinkedPairs()
    {
        return $this->aot_launcher->getLinkedPairs();
    }
}