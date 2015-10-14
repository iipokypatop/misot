<?php

namespace Aot\MivarTextSemantic\SyntaxParser;

use Aot\MivarTextSemantic\Dictionary\DMivarDictionary;
use Aot\MivarTextSemantic\MivarSpaceWdw;
use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\RegParser\RegClasses\Text;
use Aot\MivarTextSemantic\Word;

/**
 * class ParserManager
 *
 * @brief Абстрактный класс для администирования и запуска правил парсера
 *
 */
class ParserManager
{

    public $current_dictionary;
    /**< Словарь */

    public $train_system_mode = false;
    /**< режим обучения системы */

    public $context = 1;
    /**< источник данных */

    public $vl = 1;
    /**< источник данных */

    public $origin = "";
    /**< источник данных */

    public $count_saved_rules = 0;
    /**< количество записанных правил */

    public $syntax_db = null;
    /**< объект для работы с синтаксической БД шаблонов */

    public $reg_parser;
    /**< Парсим текст на слова, предложения и т.д. */

    public $path_to_rules = "";
    /**< Путь к правилам от директории с абстрактным классом*/

    /**< Массив применяемых правил */

    public $array_rule = array();

    /**
     * @brief Конструктор класса
     * @param $train_system_mode - режим обучения системы
     * @param $context - контекст
     * @param $vl - виртуальная личность
     * @param $origin - источник данных
     * @param $connection_string - строка подключения к БД
     */

    public function __construct($train_system_mode = false, $context = 1, $vl = 1, $origin = "", $connection_string = \Aot\MivarTextSemantic\Constants::DB_MIVAR_INTELLIGENCE)
    {
        //$this->reg_parser = new RegParser;
        $this->reg_parser = new Text;
        $this->current_dictionary = new DMivarDictionary(array());
        foreach ($this->array_rule as $rule) {
            if (file_exists(__DIR__ . "{$this->path_to_rules}/{$rule}.php")) {
                require_once(__DIR__ . "{$this->path_to_rules}/{$rule}.php");
            }
        }
        $this->syntax_db = ($this->train_system_mode = $train_system_mode) ?
            new SyntaxDb($connection_string) : null;
        $this->context = $context;
        $this->vl = $vl;
        $this->origin = $origin;
        $this->count_saved_rules = 0;
    }

    /**
     * @brief Создание синтаксической модели текста
     * @param $text - текст
     */

    public function create_syntax_model($text)
    {
        $this->reg_parser->parse_text($text);
        return $this->create_dictionary_word()->merge_syntax_model_sentence();

    }

    /**
     * @brief Создание синтаксической модели текста по предложениям и объединение синтаксических моделей
     * @return синтаксическая модель предложения
     */

    protected function merge_syntax_model_sentence()
    {
        $syntax_model = new MivarSpaceWdwOOz;
        foreach ($this->reg_parser->get_sentences() as $sentence) {
            $syntax_model_sentence = $this->create_syntax_model_sentence($sentence);
            $syntax_model->add_to_space($syntax_model_sentence);
        }
        if ($this->train_system_mode) {
            //$this->view($this->syntax_db);
            $this->count_saved_rules = $this->syntax_db->save_points_to_db($this->context, $this->vl, $this->origin);
        }
        return $syntax_model;
    }

    /**
     * @brief Создание синтаксической модели предложения
     * @param $sentence - распарсенное предложение на слова
     * @return $syntax_model_sentence синтаксическая модель предложения
     */

    protected function create_syntax_model_sentence($sentence)
    {
        $syntax_model_sentence = new MivarSpaceWdwOOz;
        $wdw = $this->create_sentence_space($sentence);
        foreach ($this->array_rule as $rule) {

            $syntax_model_rule = new $rule($this->train_system_mode, $this->syntax_db);
            if ($syntax_model_rule->analyze(clone $wdw)) {
                $syntax_model_sentence->add_to_space($syntax_model_rule->perfom());
            }
        }

        return $syntax_model_sentence;
    }

    /**
     * @brief Создание словаря из текущих слов в тексте
     */

    public function create_dictionary_word()
    {
        $this->current_dictionary = new DMivarDictionary($this->reg_parser->get_text_words());
        return $this;
    }

    /**
     * @brief Создание пространства для слов в предложении
     *
     * @param $sentence - предложение
     * @return MivarSpaceWdw пространство wdw
     */

    public function create_sentence_space($sentence)
    {
        $sentence_space = new MivarSpaceWdw();
        foreach ($sentence->get_words() as $word) {
            if (isset($this->current_dictionary->array_current_dictionary[$word->text])) {
                $count_dw = count($this->current_dictionary->array_current_dictionary[$word->text]) - 2;
                foreach ($this->current_dictionary->array_current_dictionary[$word->text] as $key_dict => $dw) {
                    if ($key_dict != 'id_word_classes' && $key_dict != 'initial_forms') {
                        $sentence_space->add_point_wdw($word->index,
                            $sentence->index,
                            $count_dw,
                            new Word($word->index,
                                $word->text,
                                $sentence->id_sentence,
                                $word->get_flags_array()),
                            $dw);
                    }
                }
            } else {
                $sentence_space->add_point_wdw($word->index,
                    $sentence->index,
                    null,
                    new Word($word->index,
                        $word->text,
                        $sentence->id_sentence,
                        $word->get_flags_array()),
                    null);
            }
        }
        return $sentence_space;
    }

    /**
     * @brief Отладка. Вывод на экран и завершение работы
     */

    protected function view($data)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre/>";
        die;
    }
}