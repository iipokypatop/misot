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
    protected $sentence_words = [];

    const PATTERN_SENTENCE_DELIMITER = "/([\\.\\!\\?])(\\s[А-ЯЁ]|$)/u";
    const END_SENTENCE_TEMPLATE = " {{end_%s}}\n";

    protected static $sentence_needle = [
        "/\\s*\\,\\s*/u",
        "/\\{\\{/u",
        "/\\}\\}/u",
    ];
    protected static $sentence_replacement = [
        " , ",
        " {{",
        "}} ",
    ];

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

        // разбиваем текст на предложения
        $this->sentences = $this->splitInSentences($text);


        // разбиваем предложения на слова
        $this->sentence_words = $this->splitInWords($this->sentences);


        ///////////////
//        echo "\n" . $origin_text;
//        echo "\n" . $text . "\n";
//        print_r($this->registry);
//
//
//        print_r($this->sentences);
//        print_r($this->sentence_words);


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
     * @param string $text
     * @return array
     */
    protected function splitInSentences($text)
    {
        preg_match_all(static::PATTERN_SENTENCE_DELIMITER, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $shift_pos = 0; // смещение позиции
        foreach ($matches as $match) {
            $text = substr_replace(
                $text,
                sprintf(static::END_SENTENCE_TEMPLATE, $match[1][0]),
                $match[1][1] + $shift_pos, strlen($match[1][0])
            );
            $shift_pos += strlen($match[1][0]) + 9; // +1 - тк пробел

        }
        // разбиваем текст на предложения
        $sentences = explode("\n", $text);
        // чистим
        foreach ($sentences as $key => $sentence) {
            if( strpos($sentence, "}}.") !== FALSE )
            {
                $sentences[$key] = str_replace("}}.", "}}", $sentence);
            }
            $sentences[$key] = trim($sentences[$key]);
            if( $sentences[$key] === '' ){
                unset($sentences[$key]);
            }
        }

        return $sentences;
    }

    /**
     * @param array $sentences
     * @return array
     */
    protected function splitInWords(array $sentences)
    {
        // разбиваем предложения на слова
        $sentence_words = [];
        foreach ($sentences as $key => $sentence) {
            $sentence = preg_replace( static::$sentence_needle, static::$sentence_replacement, $sentence);
            $sentence_words[$key] = preg_split("/\\s+/u", $sentence);
            $sentence_words[$key] = array_filter($sentence_words[$key]); // чистим от пустых элементов
        }
        return $sentence_words;
    }
}