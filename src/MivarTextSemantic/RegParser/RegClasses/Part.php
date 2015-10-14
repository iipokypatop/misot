<?php


namespace Aot\MivarTextSemantic\RegParser\RegClasses;
use Aot\MivarTextSemantic\RegParser\TextUnit;

/**
 * class Part
 *
 * @brief Класс для работы с частью
 * @author Елисеев Д.В.
 *
 */
class Part extends TextUnit
{
    public $number;
    /**номер части */

    /**< Массив регулярок для разбивки части на абзацы и функция обработки найденных шаблонов */

    protected $array_reg = array("\.(\s)*\n" => "add_node_with_end");

    /**
     * @brief Конструктор класса
     * @param $index - индекс текстового блока в массиве
     * @param $text - текст блока
     * @param $number - номер части
     */

    public function __construct($index = 0,
                                $text = "",
                                $number = null)
    {
        $this->number = (is_string($number)) ? $number : null;
        parent::__construct($index,
            $text);
    }

    /**
     * @brief Метод для парсинга текста по регулярным выражениям
     *
     * @param $text - текст
     * @return массив - распарсенный текст
     */

    /*public function parse_text($txt) {
            //$parse_text = array();
            //$this->current_list_words = array();
            if ($txt) {
                    $this->text = trim($txt);
                    $this->items = array();

                    //Разбивка текста на части

                    $this->parse_text_unit($this, $this->array_reg);*/
    /*if (isset($parse_text['items']) && is_array($parse_text['items'])) {
            foreach ($parse_text['items'] as &$part) {
                    $part = $this->parse_text_unit($part, $this->array_reg_paragraph, "paragraph");
                    if (isset($part['items']) && is_array($part['items'])) {
                            foreach ($part['items'] as &$paragraph) {
                                    $paragraph = $this->parse_text_unit($paragraph, $this->array_reg_sentence, "sentence");
                                    if (isset($paragraph['items']) && is_array($paragraph['items'])) {
                                            foreach ($paragraph['items'] as &$sentence) {
                                                    $sentence = $this->parse_text_unit($sentence, $this->array_reg_word, "word");
                                                    if (isset($sentence['items']) && is_array($sentence['items'])) {
                                                            foreach ($sentence['items'] as &$word) {
                                                                    if (!isset($word['data']) && !isset($word['name_fio']) &&
                            !isset($word['stop']) && !isset($word['cut']) &&
                            !isset($word['abbr'])) {
                            $word['text'] = mb_strtolower($word['text'], 'UTF-8');
                                                                            $text_word = $word['text'];
                                                                            $this->current_list_words[$text_word] = $text_word;
                                                                    }
                        $word['id_sentence'] = $sentence['id_sentence'];
                                                            }
                                                    }
                                            }
                                    }
                            }
                    }
            }
    }*/
    /* }
     return $this;
}*/

    /**
     * @brief Создаём объект для параграфа с регулярным выражением в конце
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */

    public function add_node_with_end($minPoz, $lastMinPoz)
    {

        if ($minPoz["rezText"]) {
            if (!isset($minPoz["regText"]))
                $minPoz["regText"] = "";
            $this->items[] = new Paragraph(count($this->items),
                $minPoz["rezText"] . mb_substr($minPoz["regText"], 0, 1, 'UTF-8'));
        }
        return $this;
    }
}