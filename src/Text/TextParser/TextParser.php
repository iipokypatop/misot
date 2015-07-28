<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 19:21
 */

namespace Aot\Text\TextParser;


use Aot\Text\TextParser\Filters\FilterNoValid;
use Aot\Text\TextParser\Filters\FilterSpaces;
use Aot\Text\TextParser\Replacement\FIO;
use Aot\Text\TextParser\Replacement\ReplaceHooks;
use Aot\Text\TextParser\Replacement\ReplaceNumbers;
use Aot\Text\TextParser\Replacement\ReplaceShort;
use Symfony\Component\Yaml\Exception\RuntimeException;

class TextParser
{

    protected $registry = [];
    protected $alerts = [];
    protected $sentences = [];



    public static function create()
    {
        return new static();
    }

    const PATTERN_SENTENCE_DELIMITER = "/\\.|\\!|\\?/";

    public function execute($text)
    {
        $logger = null;

        $origin_text = $text;
        // чистим от лишних пробельных символов
        $filterSpaces = FilterSpaces::create($logger);
        $text = $filterSpaces->filter($text);

        // убираем невалидные символы
        $filterNoValid = FilterNoValid::create();
        $text = $filterNoValid->filter($text);

        // ФИО
        $replaceFIO = FIO::create($this->registry);
        $text = $replaceFIO->replace($text);
       /// $this->registry = $replaceFIO->getRegistry();

        // скобки
        $replaceHooks = ReplaceHooks::create($this->registry);
        $text = $replaceHooks->replace($text);
        //$this->registry = $replaceHooks->getRegistry();

        // сокращения
        $replaceShort = ReplaceShort::create($this->registry);
        $text = $replaceShort->replace($text);
        //$this->registry = $replaceShort->getRegistry();

        // числительные
        $replaceNumbers = ReplaceNumbers::create($this->registry);
        $text = $replaceNumbers->replace($text);
        //$this->registry = $replaceNumbers->getRegistry();


        // разбиваем текст на предложения
        $sentences = preg_split(static::PATTERN_SENTENCE_DELIMITER, $text);

        // разбиваем предложения на слова
        $sentence_words = [];
        foreach ($sentences as $key => $sentence) {
            $sentence = trim(preg_replace(
                    [
                        "/\\s*\\,\\s*/u",
                        "/\\{\\{/u",
                        "/\\}\\}/u",
                    ],
                    [
                        " , ",
                        " {{",
                        "}} ",
                    ],
                    $sentence
                )
            );
            $sentence_words[$key] = preg_split("/\\s+/u", $sentence);
        }
        $this->sentences = $sentence_words;

        ///////////////
        echo "\n" . $origin_text;
        echo "\n" . $text . "\n";
        print_r($this->registry);


        print_r($sentences);
        print_r($sentence_words);


    }

    public function render()
    {
        $string_sentence = [];
        foreach ($this->sentences as $words_sentence) {
            foreach ($words_sentence as $word) {
                if (preg_match("/\\{\\{(\\d+)\\}\\}/u", $word, $match)) {
                    if (empty($this->registry[$match[1]])) {
                        throw new RuntimeException('Неизвестный индекс');
                    }

                    $word = $this->registry[$match[1]];
                }
                $string_sentence[]= $word . " ";
            }
            $string_sentence[]= ".";
        }

        return join('', $string_sentence);

    }
}