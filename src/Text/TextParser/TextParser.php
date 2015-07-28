<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 19:21
 */

namespace Aot\Text\TextParser;


use Aot\Text\TextParser\Filters\NoValid;
use Aot\Text\TextParser\Filters\Spaces;
use Aot\Text\TextParser\Replacement\FIO;
use Aot\Text\TextParser\Replacement\Hooks;
use Aot\Text\TextParser\Replacement\Numbers;
use Aot\Text\TextParser\Replacement\Short;
use Symfony\Component\Yaml\Exception\RuntimeException;

class TextParser
{

    protected $registry = [];
    protected $alerts = [];
    protected $sentences = [];

    const PATTERN_SENTENCE_DELIMITER = "/\\.|\\!|\\?/";

    protected $filterSpaces;
    protected $filterNoValid;

    protected $replaceFIO;
    protected $replaceHooks;
    protected $replaceShort;
    protected $replaceNumbers;


    public static function create()
    {
        return new static();
    }

    public function __construct()
    {

        $logger = null;
//        $this->filterSpaces = Spaces::create($logger);
//        $this->filterNoValid = NoValid::create($logger);

        $this->registry = Registry::create();
        $this->replaceFIO = FIO::create($this->registry);
        $this->replaceHooks = Hooks::create($this->registry);
        $this->replaceShort = Short::create($this->registry);
        $this->replaceNumbers = Numbers::create($this->registry);
    }

    public function execute($text)
    {

        $origin_text = $text;

        // чистим от лишних пробельных символов
//        $text = $this->filterSpaces->filter($text);

        // убираем невалидные символы
//        $text = $this->filterNoValid->filter($text);

        // ФИО
        $text = $this->replaceFIO->replace($text);

        // скобки
        $text = $this->replaceHooks->replace($text);

        // сокращения
        $text = $this->replaceShort->replace($text);

        // числительные
        $text = $this->replaceNumbers->replace($text);


        // разбиваем текст на предложения
        $sentences = preg_split(static::PATTERN_SENTENCE_DELIMITER, $text);

        // разбиваем предложения на слова
        $sentence_words = [];
        foreach ($sentences as $key => $sentence) {
            $sentence = trim(preg_replace(
                    [
                        "/\\s*\\,\\s*/u",
                        "/\\{\\%/u",
                        "/\\%\\}/u",
                    ],
                    [
                        " , ",
                        " {%",
                        "%} ",
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