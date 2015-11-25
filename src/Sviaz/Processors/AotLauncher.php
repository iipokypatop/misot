<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 19:32
 */

namespace Aot\Sviaz\Processors;


class AotLauncher
{
    /** @var \Sentence_space_SP_Rel[]  */
    protected $syntax_model = [];

    public static function create($text)
    {
        return new static($text);
    }

    protected function __construct($text)
    {
        assert(is_string($text));
        $this->syntax_model = $this->createSyntaxModel($text);
    }


    /**
     * Получение синтаксической модели через АОТ
     * @return \Sentence_space_SP_Rel[]
     */
    public function getSyntaxModel()
    {
        return $this->syntax_model;
    }

    /**
     * @return bool
     */
    public function isModelEmpty(){
        return empty($this->syntax_model);
    }

    /**
     * @return \Sentence_space_SP_Rel[]
     */
    public function getLinkedPairs()
    {
        $linked_pairs = [];
        foreach ($this->syntax_model as $key => $point) {
            $linked_pairs[$point->Oz][$point->direction] = $point;
        }
        return $linked_pairs;
    }


    /**
     * Создание синтаксической модели через АОТ
     * @param $text
     * @return array
     */
    protected function createSyntaxModel($text)
    {
        assert(is_string($text));

        $mivar = new \DMivarText(['txt' => $text]);

        $mivar->syntax_model();

        return $mivar->getSyntaxModel();
    }
}