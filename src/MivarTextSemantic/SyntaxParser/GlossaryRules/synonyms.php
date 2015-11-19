<?php

namespace Aot\MivarTextSemantic\SyntaxParser\GlossaryRules;

use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;

/**
 *
 * class synonyms
 *
 * @brief Класс для поиска общего и частного
 *
 */
class synonyms extends SyntaxRule
{

    protected $synonyms_part_gen; //**< Найденные частное и общие */

    /**
     * @brief Метод анализа на возможность запуска правила
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */
    public function analyze($wdw)
    {
        //$this->view($wdw);
        $result = false;
        $this->error = "";
        $this->sence_filter = false;
        $this->wdw = $wdw;
        //$this->create_kw_index($wdw);
        $this->find_points_wdw = $this->find_morph_synonyms($wdw);
        if (isset($this->synonyms_part_gen['participal_synonym'], $this->synonyms_part_gen['general_synonym'])) {
            $result = true;
        }
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */
    public function perfom()
    {
        //$this->syntax_model_rule = array();
        $this->syntax_model_rule = new MivarSpaceWdwOOz();
        if (isset($this->synonyms_part_gen['participal_synonym'], $this->synonyms_part_gen['general_synonym'])) {
            $this->set_synonyms();
        }
        return $this->syntax_model_rule;
    }

    /**
     * @brief Связываение одного синонима с другими
     */
    protected function set_synonyms()
    {
        $uuid = uniqid();
        foreach ($this->synonyms_part_gen['participal_synonym'] as $part_key => $part_word) {
            foreach ($this->synonyms_part_gen['general_synonym'] as $gen_key => $gen_word) {
                if ($part_word->kw != $gen_word->kw) {
                    $gen_word->O = $part_word->O = 'synonym';
                    $gen_word->Oz = $part_word->Oz = $uuid;
                    $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $part_word, $gen_word,
                        $uuid);
                }
            }
        }
        return $this;
    }

    public function sence_filter()
    {

    }

    /*
     * @brief число знаков препинания в массиве
     * @param $sentence_space - массив, в котором ищем
     * @param $pnt - массив знаков препинания, который ищем
     * @return количество указанных знаков в массиве
     */
    protected function count_pnt($sentence_space = array(), $start_ind, $pnt = array())
    {
        $result = 0;
        for ($i = $start_ind; $i < count($sentence_space->get_space()); $i++) {
            if (isset($sentence_space->get_space()[$i])) {
                $point_word = $sentence_space->get_space()[$i];
                if (isset($point_word->w->word) && in_array($point_word->w->word, $pnt)) {
                    $result++;
                    if ($point_word->w->word == "и") {//увеличиваем $i на 2, т.к. "и" в массиве встречается 3 раза
                        $i++;
                        $i++;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @brief Поиск общих синонимов, совпадающих с частным по части речи
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     * @return $sentence_space_SP - выделенное пространство
     */
    protected function find_morph_synonyms($sentence_space)
    {
        $sentence_space_SP = array();
        $this->synonyms_part_gen = array();
        $from_key = -1;
        foreach ($sentence_space->get_space() as $key => $point_word) {
            if ($point_word->w->word == '-') {
                //$from_key = $key+1;
                $index_key = $point_word->key_point + 1;
                $from_key = $point_word->kw + 1;
                break;
            }
            $point_word->ps = 'participal_synonym';
            //$sentence_space_SP[$key] = $point_word;
            if (!isset($sentence_space_SP[0]->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr[\Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID])) {

                $sentence_space_SP[0] = $point_word;
                if (isset($point_word->ps)) {
                    $this->synonyms_part_gen[$point_word->ps][$key] = $point_word;
                }
            }
        }

        $result_word = array();
        if (end($sentence_space->get_space())->kw - $from_key - $this->count_pnt($sentence_space, $from_key,
                array(",", "и")) == $this->count_pnt($sentence_space, $from_key, array(",", "и"))
        ) {

            if (isset($sentence_space_SP[0]->dw)) {
                //for ($i=$from_key; $i<count($sentence_space->get_space()); $i++){
                for ($i = $index_key; $i < count($sentence_space->get_space()); $i++) {
                    if (isset($sentence_space->get_space()[$i])) {
                        $point_word = $sentence_space->get_space()[$i];
                        if (isset($point_word->dw)) {
                            if ($point_word->dw->check_parameter($sentence_space_SP[0]->dw->id_word_class, null,
                                null)
                            ) {
                                if ($this->combination_participant_general($sentence_space_SP[0], $point_word)) {
                                    $point_word->ps = 'general_synonym';
                                    $sentence_space_SP[$i] = $point_word;
                                }
                                if (isset($point_word->ps)) {
                                    $this->synonyms_part_gen[$point_word->ps][$i] = $point_word;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $sentence_space_SP;
    }

    /**
     * @brief Согласование частного и общего синонимов
     * @param $par_word - частный синоним
     * @param $gen_word - общий синоним
     * @return - возвращает булево значение true если существительное сочетается с глаголом иначе false
     */
    protected function combination_participant_general($par_word, $gen_word)
    {
        $res = false;
        if (isset($gen_word->dw->id_word_class) && isset($par_word->dw->id_word_class) &&
            $gen_word->dw->id_word_class == $par_word->dw->id_word_class
        ) {
            if (isset($gen_word->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr[\Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID]) && $gen_word->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr[\Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID] == \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID &&
                isset($par_word->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr[\Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID]) && $par_word->dw->parameters[\Aot\MivarTextSemantic\Constants::CASE_ID]->id_value_attr[\Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID] == \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID
            ) {
                return /*$res =*/
                    $this->compare_parameters($par_word->dw, $gen_word->dw,
                        array(\Aot\MivarTextSemantic\Constants::NUMBER_ID));
            } else {
                return /*$res =*/
                    $this->compare_parameters($par_word->dw, $gen_word->dw,
                        array(\Aot\MivarTextSemantic\Constants::NUMBER_ID));
            }
        }
        return $res;
    }
}

?>