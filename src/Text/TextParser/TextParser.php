<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
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

class TextParser
{
    /** Registry */
    protected $registry; // реестр замен и тд
    protected $sentences = []; // массив предложений
    protected $sentence_words = []; // массив слов предложений
    protected $processed_text; // обработанный текст

    const PATTERN_SENTENCE_DELIMITER = "/([\\.\\!\\?])(\\s[А-ЯЁ]|$)/u";
    const END_SENTENCE_TEMPLATE = " %s\n";
    const START_TEMPLATE = '{{';
    const END_TEMPLATE = '}}';
    const SEPARATOR = ' ';

    /**
     * @var \Aot\Text\TextParser\Logger
     */
    protected $logger;

    protected static $sentence_needle = [
        "/\\s*[\\,\"\\'\\`\\‘\\‛\\’\\«\\»\\‹\\›\\„\\“\\‟\\”\\:\\;\\(\\)]\\s*/u",
        "/\\{\\{/u",
        "/\\}\\}/u",
    ];

    /** @var Spaces */
    protected $filterSpaces;

    /** @var NoValid */
    protected $filterNoValid;

    /** @var FIO */
    protected $replaceFIO;

    /** @var Hooks */
    protected $replaceHooks;

    /** @var Short */
    protected $replaceShort;

    /** @var Numbers */
    protected $replaceNumbers;

    protected $sentences_without_patterns;


    /** @var  string */
    protected $raw_input_text;

    protected $symbol_map = [];

    /**
     * @var \Aot\Text\TextParser\PostHooks\Base[]
     */
    protected $hooks = [];

    public function addPostHook(\Aot\Text\TextParser\PostHooks\Base $hook)
    {
        $this->hooks[] = $hook;
    }

    public static function create()
    {
        return new static();
    }

    /**
     * TextParser constructor.
     */
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

    /**
     * Прогоняем текст через фильтры
     * @param $text
     */
    public function execute($text)
    {
        $this->raw_input_text = $text;

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

        // преобразования в тексте
        $text = $this->textСonversion($text);

        $this->processed_text = $text;
    }

    /**
     * Обрабатываем текст, прогнанный через фильтры
     */
    public function render()
    {
        // разбиваем текст на предложения
        $this->sentences = $this->splitInSentences($this->processed_text);

        // разбиваем предложения на слова
        $this->sentence_words = $this->splitInWords($this->sentences);

        // подменяем обратно замененные шаблоны на слова из реестра
        $this->replacePatterns();

        foreach ($this->hooks as $hook) {
            $hook->run($this);
        }
    }

    /**
     * Разбиение текста на предложения
     * @param string $text
     * @return array
     */
    protected function splitInSentences($text)
    {
        assert(is_string($text));
        preg_match_all(static::PATTERN_SENTENCE_DELIMITER, $text, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $shift_pos = 0; // смещение позиции
        foreach ($matches as $match) {
            $text = substr_replace(
                $text,
                sprintf(static::END_SENTENCE_TEMPLATE, $match[1][0]),
                $match[1][1] + $shift_pos, strlen($match[1][0])
            );
            $shift_pos += strlen($match[1][0]) + 1; // +1 - тк пробел

        }
        // разбиваем текст на предложения
        $sentences = explode("\n", $text);
        // чистим
        foreach ($sentences as $key => $sentence) {
            if (strpos($sentence, static::END_TEMPLATE . ".") !== false) {
                $sentences[$key] = str_replace(static::END_TEMPLATE . ".", static::END_TEMPLATE, $sentence);
            }
            $sentences[$key] = trim($sentences[$key]);
            if ($sentences[$key] === '') {
                unset($sentences[$key]);
            }
        }

        return $sentences;
    }

    /**
     * Разбиение предложений на слова
     * @param array $sentences
     * @return array
     */
    protected function splitInWords(array $sentences)
    {
        // разбиваем предложения на слова
        $sentence_words = [];
        foreach ($sentences as $key => $sentence) {
            $sentence = preg_replace_callback(
                static::$sentence_needle,
                function ($match) {
                    if ($match[0] === '{{') {
                        return " " . $match[0];
                    } elseif ($match[0] === '}}') {

                        return $match[0] . " ";
                    }
                    return " " . $match[0] . " ";
                },
                $sentence);
            $sentence_words[$key] = preg_split("/\\s+/u", $sentence);
            $sentence_words[$key] = array_filter($sentence_words[$key]); // чистим от пустых элементов
        }
        return $sentence_words;
    }

    /**
     * Подменяем шаблоны обратно на заменненые слова
     */
    protected function replacePatterns()
    {
        $sentence_words = $this->getSentenceWords();
        $registry = $this->getRegistry()->getRegistry();
        if (empty($sentence_words) || empty($registry)) {
            $this->sentences_without_patterns = $this->sentences;
            return;
        }
        foreach ($sentence_words as &$words) {
            foreach ($words as &$word) {
                if (preg_match("/\\{\\{(\\d+)\\}\\}/u", $word, $match)) {
                    if (!isset($registry[$match[1]])) {
                        throw new \RuntimeException('Неизвестный индекс');
                    }
                    $word = $registry[$match[1]];
                }
            }
            unset($word);
        }
        unset($words);

        $sentences = $this->sentences;
        $new_sentences = [];
        foreach ($sentences as $index => $sentence) {
            foreach ($registry as $key => $item) {
                $sentence = str_replace("{{" . ($key) . "}}", $item, $sentence);
            }
            $new_sentences[$index] = $sentence;
        }

        $this->sentences_without_patterns = $new_sentences;
        $this->sentence_words = $sentence_words;
    }

    /**
     * @return string
     */
    public function getProcessedText()
    {
        return $this->processed_text;
    }

    /**
     * @param string $processed_text
     */
    protected function setProcessedText($processed_text)
    {
        $this->processed_text = $processed_text;
    }

    /**
     * @return array
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @return array
     */
    public function getSentenceWords()
    {
        return $this->sentence_words;
    }

    /**
     * @return array|static
     */
    public function getRegistry()
    {
        return $this->registry;
    }


    public static function joinSentenceWordAndPunctuation(array $words_and_punct)
    {

    }

    /**
     * Преобразование в тексте
     * @param string $text
     * @return string
     */
    private function textСonversion($text)
    {
        assert(is_string($text));

        if (preg_match_all(
            "/[а-яё]+([\\.\\!\\?])([а-яёА-ЯЁ])/u",
            $text,
            $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        )
        ) {

            $shift_pos = 0; // смещение позиции
            foreach ($matches as $match) {
                $text = substr_replace(
                    $text,
                    $match[1][0] . ' ',
                    $match[1][1] + $shift_pos,
                    strlen($match[1][0])
                );

                $shift_pos += 1; // +1 - тк пробел

                $text = substr_replace(
                    $text,
                    mb_convert_case($match[2][0], MB_CASE_TITLE, "UTF-8"),
                    $match[2][1] + $shift_pos, // начало
                    strlen($match[2][0]) // длина
                );
            }
        }
        return $text;
    }

    /**
     * @param string[] $sentences
     * @return string
     */
    public static function join(array $sentences)
    {
        foreach ($sentences as $sentence) {
            assert(is_string($sentence));
        }
        return join(static::SEPARATOR, $sentences);
    }

    /**
     * @return string[]
     */
    public function getSentencesWithoutPatterns()
    {
        return $this->sentences_without_patterns;
    }

    /**
     * @return string
     */
    public function getRawInputText()
    {
        return $this->raw_input_text;
    }
}