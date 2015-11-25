<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 19:19
 */

namespace Aot\Sviaz\Processors;


class SequenceWords
{
    protected $offsetManager;

    protected $sentence_words_array;
    protected $nonexistent_aot;
    protected $nonexistent_misot;

    public static function create()
    {
        return new static();
    }


    protected function __construct()
    {
        $this->offsetManager = \Aot\Sviaz\Processors\OffsetManager::create();
    }

    /**
     * @return \Aot\Sviaz\Processors\OffsetManager
     */
    public function getOffsetManager()
    {
        return $this->offsetManager;
    }

    /**
     * @return string[]
     */
    protected function getSentenceWordsArray()
    {
        return $this->sentence_words_array;
    }

    /**
     * Формируем массив слов предложения
     * @param \Aot\Sviaz\Sequence $sequence
     * @return string[]
     */
    public function getSentenceWordsBySequence(\Aot\Sviaz\Sequence $sequence)
    {
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
                $id = $this->addToSentenceWordsArray($member->getPunctuaciya()->getText());
                $this->offsetManager->refreshAotOffset($id, 1);
                $this->offsetManager->refreshMisotOffset($id);
                $this->offsetManager->addToNonexistentAot($id);
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition) {
                /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                $id = $this->addToSentenceWordsArray($member->getPredlog()->getText());
                $this->offsetManager->refreshAotOffset($id);
                // отдельно элмента предлог в мисоте нет
                $this->offsetManager->addToNonexistentMisot($id);
                $id = $this->addToSentenceWordsArray($member->getSlovo()->getText());
                $this->offsetManager->refreshAotOffset($id);
                $this->offsetManager->refreshMisotOffset($id, 1);
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
                $id = $this->addToSentenceWordsArray($member->getSlovo()->getText());
                $this->offsetManager->refreshMisotOffset($id);
                $this->offsetManager->refreshAotOffset($id);
            }
        }
        return $this->getSentenceWordsArray();
    }


    /**
     * Добавляем элемент в массив слов предложения
     * @param string $text
     * @return int
     */
    protected function addToSentenceWordsArray($text)
    {
        assert(is_string($text));
        $this->sentence_words_array[] = $text;
        end($this->sentence_words_array);
        $key = key($this->sentence_words_array);
        return $key;
    }


}