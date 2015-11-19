<?php


namespace Aot\MivarTextSemantic\RegParser\RegClasses;

use Aot\MivarTextSemantic\RegParser\TextUnit;

/**
 * class Sentence
 *
 * @brief Класс для работы с предложением
 * @author Елисеев Д.В.
 *
 */
class Sentence extends TextUnit
{

    public $endchar = "";
    /**< Символ конца предложения */
    public $id_sentence = "";
    /**< id предложения */

    /**< Массив регулярок для разбивки предложения на слова и функция обработки найденных шаблонов */

    protected $array_reg = array(
        "[^А-Яа-яA-Za-z0-9ёЁ_\-]+" => "add_word",
        "(([0-9]{1,2}\.[0-9]{1,2}\.){0,1}[0-9]{4}(\s)*г\.)" => "add_date",
        "(([А-Я]\.(\s)*){0,1}[А-Я]\.(\s)*[А-Я][а-яё]*)" => "add_name",
        "((\s)*[\(\),:;«»\[\]](\s)*)" => "add_stop",
        "((\s)+[\–\-](\s)+)" => "add_stop",
        "[А-Яа-яA-Za-z0-9ёЁ_\-]+\.(\s)+" => "add_cut"
    );

    /**
     * @brief Конструктор класса
     * @param $index - индекс текстового блока в массиве
     * @param $text - текст блока
     * @param $endchar - cимвол конца предложения
     */

    public function __construct(
        $index = 0,
        $text = "",
        $endchar = ""
    ) {
        $this->endchar = (is_string($endchar)) ? $endchar : "";
        $this->id_sentence = uniqid('', true);
        parent::__construct($index,
            $text);
    }

    /**
     * @brief Получаем список слов из текста
     *
     * @return массив слов
     */

    public function get_text_words()
    {
        $result = array();
        foreach ($this->items as $word) {
            if (!$word->has_flag()) {
                $result[$word->text] = $word->text;
            }
        }
        return $result;
    }

    /**
     * @brief Получаем список слов-объектов из текста
     *
     * @return массив слов-объектов
     */

    public function get_words()
    {
        return $this->items;
    }

    /**
     * @brief Создаём объект для слова, просто
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */

    public function add_word($minPoz, $lastMinPoz)
    {

        if ($minPoz["rezText"]) {


            $this->items[] = new \Aot\MivarTextSemantic\RegParser\RegClasses\Word(count($this->items),
                mb_strtolower($minPoz["rezText"], 'utf-8'),
                $this->addSpecialAttributesToWord($minPoz["rezText"]));
        }
        return $this;
    }

    /**
     * @brief Базовый метод для добавления различных типов слов
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @param $type - тип слова: массив (имя флага => true, если он есть)
     * @return ссылка на объект
     */

    private function add_data($minPoz, $lastMinPoz, $type)
    {
        if (!isset($lastMinPoz["regText"])) {
            $lastMinPoz["regText"] = "";
        }
        $lastMinPoz["regText"] = trim($lastMinPoz["regText"]);
        $this->items[] = new \Aot\MivarTextSemantic\RegParser\RegClasses\Word(count($this->items),
            $lastMinPoz["regText"],
            (is_array($type)) ? $type : array());
        $this->add_word($minPoz, $lastMinPoz);
        return $this;
    }

    /**
     * @brief Добавляет слово с датой
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */
    public function add_date($minPoz, $lastMinPoz)
    {
        return $this->add_data($minPoz, $lastMinPoz, array('data' => true));
    }

    /**
     * @brief Добавляет слово с имененм
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */
    public function add_name($minPoz, $lastMinPoz)
    {
        return $this->add_data($minPoz, $lastMinPoz, array('name_fio' => true));
    }

    /**
     * @brief Добавляет знак препинания
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */
    public function add_stop($minPoz, $lastMinPoz)
    {
        return $this->add_data($minPoz, $lastMinPoz, array('stop' => true));
    }

    /**
     * @brief Добавляет слово-сокращение
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */
    public function add_cut($minPoz, $lastMinPoz)
    {
        return $this->add_data($minPoz, $lastMinPoz, array('cut' => true));
    }

    /**
     * @brief Добавляет параметр, что слово является аббревиатурой. Возможно дополниить проверками других параметров
     *
     * @param $text - анализируемый текст
     * @return массив параметров
     */

    protected function addSpecialAttributesToWord($text)
    {
        $result = array();
        if (mb_ereg("[А-ЯЁ_\-]+", $text, $regText) && $regText[0] == $text) {
            if (!in_array($text, array('Я', 'У', 'С', 'К', 'О', 'В'))) {
                $result['abbr'] = true;
            }
        }
        return $result;
    }
}