<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:24
 */

namespace Aot\Text;


use Aot\RussianMorphology\Slovo;

class Matrix
{
    /** @var array */
    protected $registry = [];

    protected $sentence_matrix = [];


    protected function __construct(array $slova)
    {
        foreach ($slova as $value) {

            if (empty($value)) {
                continue;
            }

            if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                $this->appendWordsForm($value);
            }

            if ($value instanceof \Aot\RussianSyntacsis\Punctuaciya\Base) {
                $this->appendPunctuation($value);
            }
        }
    }

    /**
     * @param Slovo[] $word_forms
     */
    public function appendWordsForm(array $word_forms)
    {

        assert('!empty($word_forms)');

        foreach ($word_forms as $word_form) {
            assert('$word_form instanceof \Aot\RussianMorphology\Slovo');
            $this->register($word_form);
        }


        $this->sentence_matrix[] = $word_forms;
    }

    public function appendPunctuation(\Aot\RussianSyntacsis\Punctuaciya\Base $punctuaciya)
    {
        $this->register($punctuaciya);
        $this->sentence_matrix[] = $punctuaciya;
    }

    /**
     * @return array
     */
    public function getSentenceMatrix()
    {
        return $this->sentence_matrix;
    }

    protected function register($ob)
    {
        if (in_array($ob, $this->registry, true)) {
            throw new \RuntimeException("one word or punctuation can't be here twice " . var_export($ob, 1));
        }

        $this->registry[] = $ob;
    }


    public static function create($slova)
    {
        return new static($slova);
    }


}
