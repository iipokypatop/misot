<?php

namespace Aot\MivarTextSemantic;

/**
 * class Word
 *
 * @brief Класс для описания словоформы с морфологическими атрибутами dw
 *
 */

class Word
{

    public $kw = null;
    /**< номер слова в предложении */
    public $word = null;
    /**< слово */
    public $id_sentence = null;
    /**< id предложения */

    /**
     * Флаги (признаки), которые могут быть у слова
     */

    public $data = false;
    /**< слово является датой */
    public $name_fio = false;
    /**< слово является ФИО */
    public $stop = false;
    /**< слово является знаком препинания */
    public $cut = false;
    /**< слово является аббревиатурой */

    /**
     * @brief Конструктор класса
     * @param $kw - номер слова в предложении
     * @param $word - слово
     * @param $id_sentence - id предложения
     * @param $flags - массив флагов слова: array(ключ => значение)
     */

    public function __construct(
        $kw = null,
        $word = null,
        $id_sentence = null,
        $flags = array()
    ) {
        $this->kw = $kw;
        $this->word = $word;
        $this->id_sentence = $id_sentence;

        if (is_array($flags) && $flags) {
            foreach ($flags as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $flags[$key];
                }
            }
        }
    }


    /**
     * @autor Сараев Д.В.
     * @brief Проверка наличия в параметрах совпадеения по знаку препинания
     * @param $stop - массив знаков препинания
     * @return возвращает булево значение true в случае присутствия в word указанных признаков, иначе false
     */
    public function check_word($stop = array())
    {
        if (isset($this->word) && in_array($this->word, $stop)) {
            return true;
        }
        return false;
    }
}