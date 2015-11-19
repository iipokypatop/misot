<?php


namespace Aot\MivarTextSemantic\RegParser\RegClasses;

use Aot\MivarTextSemantic\RegParser\TextUnit;

/**
 * class Paragraph
 *
 * @brief Класс для работы с параграфом
 * @author Елисеев Д.В.
 *
 */
class Paragraph extends TextUnit
{

    /**< Массив регулярок для разбивки абзаца на предложения и функция обработки найденных шаблонов */

    protected $array_reg = array(
        "[\.\?!](\s)+[А-Я]" => "add_sentence",
        "[\.\?!](\s)*$" => "add_sentence"
    );

    /**
     * @brief Получаем список предложений из текста
     *
     * @return массив предложений
     */

    public function get_sentences()
    {
        return $this->items;
    }

    /**
     * @brief Создаём объект для предложения
     *
     * @param $minPoz - массив разобранных параметров из строки
     * @param $lastMinPoz - предыдущий массив параметров.
     * @return ссылка на объект
     */
    public function add_sentence(&$minPoz, $lastMinPoz)
    {
        if ($minPoz["rezText"]) {
            if (!isset($minPoz["regText"])) {
                $minPoz["regText"] = "";
            }
            if (isset($minPoz["reg"]) && $minPoz["reg"] == "[\.\?!](\s)+[А-Я]") {
                $minPoz["poz"] = $minPoz["poz"] - strlen(mb_substr($minPoz["regText"], -1, 1, 'UTF-8'));
            }
            $this->items[] = new Sentence(count($this->items),
                $minPoz["rezText"] . mb_substr($minPoz["regText"], 0, 1, 'UTF-8'),
                mb_substr($minPoz["regText"], 0, 1, 'UTF-8'));
        }
        return $this;
    }
}