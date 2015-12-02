<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 19:19
 */

namespace Aot\Sviaz\Processors\Aot\Sequence;


use Aot\Sviaz\Processors\Aot;

class SequenceToWordsConverter
{
    /** @var \Aot\Sviaz\Processors\Aot\OffsetManager */
    protected $offsetManager;

    protected $sentence_words_array = [];
    protected $nonexistent_aot = [];
    protected $nonexistent_misot = [];

    public static function create(\Aot\Sviaz\Sequence $sequence)
    {
        return new static($sequence);
    }


    protected function __construct(\Aot\Sviaz\Sequence $sequence)
    {
        $this->offsetManager = Aot\OffsetManager::create();

        $this->covert($sequence);
    }

    /**
     * @return \Aot\Sviaz\Processors\Aot\OffsetManager
     */
    public function getOffsetManager()
    {
        return $this->offsetManager;
    }

    /**
     * @return string[]
     */
    public function getSentenceWordsArray()
    {
        return $this->sentence_words_array;
    }

    /**
     * Формируем массив слов предложения
     * @param \Aot\Sviaz\Sequence $sequence
     * @return string[]
     */
    protected function covert(\Aot\Sviaz\Sequence $sequence)
    {
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
                $id = $this->addToSentenceWordsArray($member->getPunctuaciya()->getText());
                $this->offsetManager->increaseAotOffset();
                $this->offsetManager->addToNonexistentAot($id);
                $this->offsetManager->refreshMisotOffset();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition) {
                /** @var \Aot\Sviaz\SequenceMember\Word\WordWithPreposition $member */
                $id = $this->addToSentenceWordsArray($member->getPredlog()->getText());
                $this->offsetManager->refreshAotOffset();
                // отдельно элемента предлог в мисоте нет
                $this->offsetManager->increaseMisotOffset();
                $this->offsetManager->addToNonexistentMisot($id);
                $id = $this->addToSentenceWordsArray($member->getSlovo()->getText());
                $this->offsetManager->refreshAotOffset();
                $this->offsetManager->refreshMisotOffset();
            } elseif ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                $id = $this->addToSentenceWordsArray($member->getSlovo()->getText());
                /** @var \Aot\Sviaz\SequenceMember\Word\Base $member */
                $this->offsetManager->refreshMisotOffset();
                $this->offsetManager->refreshAotOffset();
            }
        }
//        print_r([
//            'sentence' => $this->sentence_words_array,
//            'offset_by_aot' => $this->offsetManager->offset_by_aot,
//            'nonexist_aot' => $this->offsetManager->nonexistent_aot,
//            'offset_by_misot' => $this->offsetManager->offset_by_misot,
//            'nonexist_misot' => $this->offsetManager->nonexistent_misot,
//        ]);
//        die();
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

        return key($this->sentence_words_array);
    }
}