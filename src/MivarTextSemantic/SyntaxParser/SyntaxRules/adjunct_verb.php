<?php

namespace Aot\MivarTextSemantic\SyntaxParser\SyntaxRules;
use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;

/**
 *
 * class adjunct_verb
 *
 * @brief Класс для поиска и обработки наречий и глаголов
 *
 */
class adjunct_verb extends SyntaxRule
{

    protected $adjunct_verb;
    /**< Найденные глаголы и наречия */
    //public $similar_filter = false; /**< однородные члены найдены */

    /**
     * @brief Метод анализа предложения на возможность запуска правила
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */

    public function analyze($wdw)
    {
        //$this->view($wdw);
        $result = false;
        $this->error = "";
        $this->wdw = $wdw;
        $this->sence_filter = false;
        $this->find_points_wdw = $this->find_morph_adjunct_verb($wdw);
        //$this->view($this->find_points_wdw);
        if (isset($this->adjunct_verb['adverb'], $this->adjunct_verb['predicate']))
            $result = true;
        //$this->view($this->adjunct_verb);
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */

    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();

        // Только 1 глагол

        if (isset($this->adjunct_verb['adverb'], $this->adjunct_verb['predicate']) &&
            count($this->adjunct_verb['predicate']) == 1 && count($this->adjunct_verb['adverb']) >= 1
        ) {
            $this->set_pre1_adj();
        }

        // есть однородные подлежащие

        /*else if (isset($this->complex_predicate['predicate'], $this->complex_predicate['infinitive']) &&
            count($this->complex_predicate['predicate']) > 1 && count($this->complex_predicate['infinitive']) >= 1) {
            $this->set_pre_inf();
        }*/
        //$this->view($this->syntax_model_rule);
        return $this->syntax_model_rule;
    }

    /**
     * @brief Связываение однoго глагола с наречиями
     */

    protected function set_pre1_adj()
    {
        $uuid = uniqid();
        foreach ($this->adjunct_verb['predicate'] as $pre_key => $pre_word) {
            foreach ($this->adjunct_verb['adverb'] as $inf_key => $adj_word) {
                if ($pre_word->kw != $adj_word->kw &&
                    ($adj_word->count_dw > 1 || !$this->find_ps_next($adj_word->kw + 1, array(\Aot\MivarTextSemantic\Constants::ADVERB_CLASS_ID, \Aot\MivarTextSemantic\Constants::ADJECTIVE_CLASS_ID), array(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, \Aot\MivarTextSemantic\Constants::PARTICIPLE_CLASS_ID, \Aot\MivarTextSemantic\Constants::COMMUNION_CLASS_ID)))
                ) {
                    $pre_word->O = $adj_word->O = 'adjunct_verb';
                    $pre_word->Oz = $adj_word->Oz = $uuid;
                    $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $pre_word, $adj_word, $uuid);
                    if ($this->train_system_mode) {
                        $this->syntax_db->add_syntax_relation($pre_word->dw->initial_form, $adj_word->dw->initial_form, UUID_\Aot\MivarTextSemantic\SyntaxParser\Constants::ADJUNCT_VERB_MIVAR, \Aot\MivarTextSemantic\Constants::ADJUNCT_VERB_MIVAR);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @brief Метод смысловой фильтрации полученных результатов (Если необходимо)
     */

    public function sence_filter()
    {

    }

    /**
     * @brief Поиск наречий и глаголов
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     * @return $sentence_space_SP - выделенное пространство
     */

    protected function find_morph_adjunct_verb($sentence_space)
    {
        $sentence_space_SP = array();
        $this->adjunct_verb = array();
        //$this->view($sentence_space->get_space() );
        foreach ($sentence_space->get_space() as $key => $point_word) {

            // проверяем слово на наречие

            if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::ADVERB_CLASS_ID, null, null)) {
                $point_word->ps = 'adverb';
                $sentence_space_SP[$key] = $point_word;
            } // проверяем слово на глагол

            else if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, null, null) ||
                $point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PARTICIPLE_CLASS_ID, null, null) ||
                $point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::COMMUNION_CLASS_ID, null, null)
            ) {
                $point_word->ps = 'predicate';
                $sentence_space_SP[$key] = $point_word;
            }
            if (isset($point_word->ps))
                $this->adjunct_verb[$point_word->ps][$key] = $point_word;
        }
        //$this->view($this->adjunct_verb);
        return $sentence_space_SP;
    }
}

?>
