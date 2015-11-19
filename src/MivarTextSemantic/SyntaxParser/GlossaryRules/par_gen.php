<?php

namespace Aot\MivarTextSemantic\SyntaxParser\GlossaryRules;

use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;


/**
 *
 * class par_gen
 *
 * @brief Класс для поиска общего и частного
 *
 */
class par_gen extends SyntaxRule
{

    protected $participal_general; //**< Найденные частное и общие */

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
        //$this->sence_filter = $this->sence_filter = false;
        $this->sence_filter = false;
        $this->wdw = $wdw;
        //$this->create_kw_index($wdw);

        $this->find_points_wdw = $this->find_morph_participal_general($wdw);
        if (isset($this->participal_general['participal'], $this->participal_general['general'])) {
            $result = true;
        }
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */
    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();
        //$this->syntax_model_rule = array();
        if (isset($this->participal_general['participal'], $this->participal_general['general'])) {
            $this->set_par_gen();
        }
        return $this->syntax_model_rule;
    }

    /**
     * @brief Связываение одного частного с несколькими общими
     */
    protected function set_par_gen()
    {
        $uuid = uniqid();
        foreach ($this->participal_general['participal'] as $part_key => $part_word) {
            foreach ($this->participal_general['general'] as $gen_key => $gen_word) {
                if ($part_word->kw != $gen_word->kw) {
                    $gen_word->O = $part_word->O = 'participal_general';
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

    /**
     * @brief Поиск общих, совпадающих с частным по числу
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     * @return $sentence_space_SP - выделенное пространство
     */
    protected function find_morph_participal_general($sentence_space)
    {
        $sentence_space_SP = array();
        $this->participal_general = array();
        $from_key = -1;

        foreach ($sentence_space->get_space() as $key => $point_word) {
            if ($point_word->w->word == '-') {
                //unset($sentence_space->get_space()[$key]);
                //$from_key = $key+1;
                $from_key = $point_word->kw + 1;
                break;
            }
            if (isset($point_word->dw) && $point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                    \Aot\MivarTextSemantic\Constants::CASE_ID, \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
            ) {
                $point_word->ps = 'participal';
                $sentence_space_SP[0] = $point_word;
                if (isset($point_word->ps)) {
                    $this->participal_general[$point_word->ps][$key] = $point_word;
                }
            }
            //unset($sentence_space->get_space()[$key]);
        }

        //определяем число, одушевленность и падеж частного
        $result_word = array();
        if (isset($sentence_space_SP[0]->dw)) {
            if (isset($sentence_space->get_space()[$from_key]->w) && ($sentence_space->get_space()[$from_key]->w->word == "о" || $sentence_space->get_space()[$from_key]->w->word == "об")) {
                for ($i = $from_key; $i < count($sentence_space->get_space()); $i++) {
                    $point_word = $sentence_space->get_space()[$i];
                    if (isset($point_word->dw)) {
                        if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                            \Aot\MivarTextSemantic\Constants::CASE_ID,
                            \Aot\MivarTextSemantic\Constants::CASE_PREPOSITIONAL_ID)
                        ) {
                            if ($this->combination_participant_general($sentence_space_SP[0], $point_word)) {
                                $point_word->ps = 'general';
                                $sentence_space_SP[$i] = $point_word;
                            }
                            if (isset($point_word->ps)) {
                                $this->participal_general[$point_word->ps][$i] = $point_word;
                            }
                        }
                    }
                }
            } else {
                if ($sentence_space_SP[0]->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                    \Aot\MivarTextSemantic\Constants::CASE_ID, \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                ) {//проверяем частное на имя существительное в именительном падеже
                    for ($i = $from_key; $i < count($sentence_space->get_space()); $i++) {
                        if (isset($sentence_space->get_space()[$i])) {
                            $point_word = $sentence_space->get_space()[$i];
                            // проверяем общее на имя существительное в именительном падеже
                            if (isset($point_word->dw)) {
                                if ($point_word->count_dw > 1) {
                                    //ищем общие, укоторых есть и.п. и в.п.
                                    for ($j = $from_key; $j < count($sentence_space->get_space()); $j++) {
                                        if (isset($sentence_space->get_space()[$j])) {
                                            $point_word_sub = $sentence_space->get_space()[$j];
                                            $case_accusative = false;//наличие винительного падежа
                                            if ($point_word_sub->kw == $point_word->kw &&
                                                $point_word_sub->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                                    \Aot\MivarTextSemantic\Constants::CASE_ID,
                                                    \Aot\MivarTextSemantic\Constants::CASE_ACCUSATIVE_ID) &&
                                                $this->combination_participant_general($sentence_space_SP[0],
                                                    $point_word_sub)
                                            ) {
                                                $case_accusative = true;
                                                break;
                                            }
                                        }
                                    }
                                    // берем из нашедших общие в и.п.
                                    if ($case_accusative == true && $point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                            \Aot\MivarTextSemantic\Constants::CASE_ID,
                                            \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                                    ) {
                                        //проверяем, не стоит ли после глагола, причастия, деепричастия  \Aot\MivarTextSemantic\SyntaxParser\Constants::ADJECTIVE_CLASS_ID,\Aot\MivarTextSemantic\SyntaxParser\Constants::NOUN_CLASS_ID, \Aot\MivarTextSemantic\SyntaxParser\Constants::NOUN_CLASS_ID
                                        if (!$this->find_ps_prev($point_word->kw - 1, array(
                                            \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
                                            \Aot\MivarTextSemantic\Constants::COMMUNION_CLASS_ID,
                                            \Aot\MivarTextSemantic\Constants::PARTICIPLE_CLASS_ID
                                        ), array(), array("-"))
                                        ) {
                                            if ($this->find_ps_between($point_word->kw - 1,
                                                \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                                \Aot\MivarTextSemantic\Constants::CASE_ID,
                                                \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                                            ) {
                                                if ($this->combination_participant_general($sentence_space_SP[0],
                                                    $point_word)
                                                ) {
                                                    $point_word->ps = 'general';
                                                    $sentence_space_SP[$j] = $point_word;
                                                }
                                                if (isset($point_word->ps)) {
                                                    $this->participal_general[$point_word->ps][$j] = $point_word;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                        \Aot\MivarTextSemantic\Constants::CASE_ID,
                                        \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                                    ) {
                                        if ($this->combination_participant_general($sentence_space_SP[0],
                                            $point_word)
                                        ) {
                                            $point_word->ps = 'general';
                                            $sentence_space_SP[$i] = $point_word;
                                        }
                                        if (isset($point_word->ps)) {
                                            $this->participal_general[$point_word->ps][$i] = $point_word;
                                        }
                                    }
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
     * @brief Согласование частного и общего
     * @param $par_word - слово частное
     * @param $gen_word - слово общее
     * @return - возвращает булево значение true если существительное сочетается с глаголом иначе false
     */
    protected function combination_participant_general($par_word, $gen_word)
    {
        $res = false;
        // частное и общее и в одном числе и одной одушевленности
        if (isset($gen_word->dw->id_word_class) && isset($par_word->dw->id_word_class) &&
            $gen_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID && $par_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID
        ) {
            //\Aot\MivarTextSemantic\SyntaxParser\Constants::ANIMALITY_ID
            $res = $this->compare_parameters($par_word->dw, $gen_word->dw,
                array(\Aot\MivarTextSemantic\Constants::NUMBER_ID));
        }
        return $res;
    }
}

?>