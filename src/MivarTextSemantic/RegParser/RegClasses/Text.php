<?php


namespace Aot\MivarTextSemantic\RegParser\RegClasses;

use Aot\MivarTextSemantic\RegParser\TextUnit;

/**
 * class Text
 *
 * @brief Класс для работы со всем текстом
 * @author Елисеев Д.В.
 *
 */
class Text extends TextUnit
{

    /**< Массив регулярок для разбивки текста на части и функция обработки найденных шаблонов */

    protected $array_reg = array(
        '\n(\s)*\n' => "add_text_unit",
        '(^|\n)(\s)*([0-9]+\.)+' => "add_part_number"
    );

    /**
     * @brief Метод для парсинга текста по регулярным выражениям
     *
     * @param $text - текст
     * @return ссылка на объект
     */

    public function parse_text($txt)
    {
        if ($txt) {

            // заменяем ё на е

            $txt = preg_replace("/ё/ui", "е", $txt);

            //$parse_text['name'] = 'text';
            $this->text = trim($txt);
            $this->items = array();

            //Разбивка текста на части

            $this->parse_text_unit($this, $this->array_reg);
        }
        return $this;
    }

    /**
     * @brief Создаём объект для части и добавляем в массив текста
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */

    public function add_text_unit($minPoz, $lastMinPoz)
    {
        if ($minPoz["rezText"]) {
            $this->items[] = new Part(count($this->items),
                $minPoz["rezText"]);
        }
        return $this;
    }

    /**
     * @brief Создаём объект для части c цифрой
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */

    public function add_part_number($minPoz, $lastMinPoz)
    {
        if (!isset($lastMinPoz["regText"])) {
            $lastMinPoz["regText"] = "";
        }
        mb_ereg("((\d)+\.)+", $lastMinPoz["regText"], $arr);
        $num = mb_strrchr(mb_substr($arr[0], 0, mb_strlen($arr[0]) - 1, 'UTF-8'), ".", true);
        $this->items[] = new Part(count($this->items),
            $minPoz["rezText"],
            $arr[0]);
        return $this;
    }
}