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

    const PATTERN_SENTENCE_DELIMITER = "/[\\.\\!\\?]\\s([А-ЯЁ])/u";

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

        $this->logger = Logger::create();
        $this->filterSpaces = Spaces::create($this->logger);
        $this->filterNoValid = NoValid::create($this->logger);

        $this->registry = Registry::create(); // реестр для хранения замен
        $this->replaceFIO = FIO::create($this->registry, $this->logger);
        $this->replaceHooks = Hooks::create($this->registry, $this->logger);
        $this->replaceShort = Short::create($this->registry, $this->logger);
        $this->replaceNumbers = Numbers::create($this->registry, $this->logger);
    }

    public function execute($text)
    {

        $origin_text = $text;


        // чистим от лишних пробельных символов
        $text = $this->filterSpaces->filter($text);

        // убираем невалидные символы
        $text = $this->filterNoValid->filter($text);

        // скобки
        $text = $this->replaceHooks->replace($text);

        // ФИО
        $text = $this->replaceFIO->replace($text);

        // сокращения
        $text = $this->replaceShort->replace($text);

        // числительные
        $text = $this->replaceNumbers->replace($text);
        // \{\{111\}\} -> {{1111}}

//        print_r($this->registry);
//        print_r($this->logger);
        // разбиваем текст на предложения
        $sentences = $this->splitInSentences($text);

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
//        print_r($this->registry);
//
//
//        print_r($sentences);
//        print_r($sentence_words);


    }
//{{DELIMITER}}
// notice -> возможно продолжение предложения
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
                $string_sentence[] = $word . " ";
            }
            $string_sentence[] = ".";
        }

        return join('', $string_sentence);

    }

    /**
     * @param $text
     * @return array
     */
    private function splitInSentences($text)
    {
        $sentences = preg_split(static::PATTERN_SENTENCE_DELIMITER, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);

        print_r($sentences);
        $arr_sentences = [];
        $temp_char = '';
        foreach ($sentences as $key => $value) {
            // каждый четный элемент содержит первую букву следующего предложения
            if (($key % 2) === 1) {
                $temp_char = $value[0];
                continue;
            }
            $arr_sentences[] = $temp_char . $value[0];


        }
        print_r($arr_sentences);
        die('fff');
        return $sentences;
    }
}