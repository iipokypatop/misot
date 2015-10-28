<?php

namespace Aot\MivarTextSemantic\SyntaxParser\SyntaxRules;
use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;


/**
 *
 * class complex_predicate
 *
 * @brief Класс для поиска сложных сказуемых
 *
 */
class complex_predicate extends SyntaxRule
{

    protected $complex_predicate;
    /**< Найденные глаголы и инфинитивы */
    //public $similar_filter = false; /**< однородные члены найдены */

    /**
     * @brief Метод анализа предложения на возможность запуска правила
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */

    public function analyze($wdw)
    {
        $result = false;
        $this->error = "";
        $this->wdw = $wdw;
        $this->sence_filter = false;
        $this->find_points_wdw = $this->find_morph_complex_predicate($wdw);
        if (isset($this->complex_predicate['infinitive'], $this->complex_predicate['predicate'])) {
            $result = true;
        }
        //$this->view($this->complex_predicate);
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */

    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();

        // нет однородных глаголов не в инфинитиве

        if (isset($this->complex_predicate['infinitive'], $this->complex_predicate['predicate']) &&
            count($this->complex_predicate['predicate']) == 1 && count($this->complex_predicate['infinitive']) >= 1
        ) {
            $this->set_pre1_inf();
        } // есть однородные глаголы

        else {
            if (isset($this->complex_predicate['predicate'], $this->complex_predicate['infinitive']) &&
                count($this->complex_predicate['predicate']) > 1 && count($this->complex_predicate['infinitive']) >= 1
            ) {
                $this->set_pre_inf();
            }
        }
        //$this->view($this->syntax_model_rule);
        return $this->syntax_model_rule;
    }

    /**
     * @brief Связываение один глагол с одним или несколькими инфинитивами
     */

    protected function set_pre1_inf()
    {
        $uuid = uniqid();
        foreach ($this->complex_predicate['predicate'] as $pre_key => $pre_word) {
            foreach ($this->complex_predicate['infinitive'] as $inf_key => $inf_word) {
                if ($pre_word->kw != $inf_word->kw &&
                    ($pre_word->count_dw == 1 || !$this->find_ps_prev($pre_word->kw - 1,
                            array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID), array(
                                \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
                                \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID
                            )))
                ) {
                    $pre_word->O = $inf_word->O = 'complex_predicate';
                    $pre_word->Oz = $inf_word->Oz = $uuid;
                    $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $pre_word, $inf_word,
                        $uuid);
                    if ($this->train_system_mode) {
                        $this->syntax_db->add_syntax_relation($pre_word->dw->initial_form, $inf_word->dw->initial_form,
                            UUID_\Aot\MivarTextSemantic\SyntaxParser\Constants::COMPLEX_PREDICATE_MIVAR,
                            \Aot\MivarTextSemantic\Constants::COMPLEX_PREDICATE_MIVAR);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @brief Связываение нескольких глаголов с инфинитивами
     */

    protected function set_pre_inf()
    {
        $blocs = $this->complex_predicate_blocs($this->find_points_wdw);
        $error = false;
        //$this->view($this->complex_predicate);
        // Идём по блокам с инфинитивами и ещем, к какому глаголу они могут быть привязаны

        if (isset($blocs['infinitive_block'])) {
            foreach ($blocs['infinitive_block'] as $key_block_inf => $block_inf) {
                $uuid = uniqid();
                $inf_word = current($block_inf);
                $kw_inf = $inf_word->kw;
                $kw_pre = -1;
                $predicate_to_link = false;
                foreach ($this->complex_predicate['predicate'] as $pre_key => $pre_word) {
                    // Если глагол находится перед инфинитивами
                    if ($pre_word->kw < $kw_inf && $pre_word->kw > $kw_pre) {
                        if ($pre_word->count_dw == 1 || !$this->find_ps_prev($pre_word->kw - 1,
                                array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID), array(
                                    \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
                                    \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                                    \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID
                                ))
                        ) {
                            $kw_pre = $pre_word->kw;
                            $predicate_to_link = $pre_word;
                        }
                    }
                }
                if ($predicate_to_link) {
                    foreach ($block_inf as $inf_word) {
                        if ($predicate_to_link->kw != $inf_word->kw) {
                            $predicate_to_link->O = $inf_word->O = 'complex_predicate';
                            $predicate_to_link->Oz = $inf_word->Oz = $uuid;
                            $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule,
                                $predicate_to_link, $inf_word, $uuid);
                        }
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
     * @brief Поиск сложных сказуемых (глаголов и глаголов в инфинитиве)
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     * @return $sentence_space_SP - выделенное пространство
     */

    protected function find_morph_complex_predicate($sentence_space)
    {
        $sentence_space_SP = array();
        $this->complex_predicate = array();
        foreach ($sentence_space->get_space() as $key => $point_word) {

            // проверяем слово на глагол

            if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, null, null)) {

                // инфинитив или нет
                if (in_array(mb_substr($point_word->w->word, -2, 2, 'utf-8'), array('ть', 'чь')) ||
                    in_array(mb_substr($point_word->w->word, -4, 4, 'utf-8'), array('ться', 'чься'))
                ) {
                    $point_word->ps = 'infinitive';
                } //$point_word['parameters']['inf'] = 'true';
                else {
                    $point_word->ps = 'predicate';
                }
                $sentence_space_SP[$key] = $point_word;
            }
            if (isset($point_word->ps)) {
                $this->complex_predicate[$point_word->ps][$key] = $point_word;
            }
        }
        return $sentence_space_SP;
    }

    /**
     * @brief Выделение однородных блоков
     * @param $find_points_wdw - пространство отфильтрованных слов и их морфологических признаков
     * @return $blocs найденные однородные блоки
     */

    protected function complex_predicate_blocs($find_points_wdw)
    {
        $infinitive = 0;
        $predicate = 0;
        $infinitive_blocs = array();
        $predicate_blocs = array();
        foreach ($find_points_wdw as $point_word) {
            if ($point_word->ps == 'infinitive' && !$predicate) {
                $infinitive++;
                $infinitive_bloc[] = $point_word;
            } else {
                if ($point_word->ps == 'infinitive' && $predicate) {
                    $predicate_blocs[] = $predicate_bloc;
                    $predicate = 0;
                    $infinitive++;
                    $infinitive_bloc = array();
                    $infinitive_bloc[] = $point_word;
                } else {
                    if ($point_word->ps == 'predicate' && !$infinitive) {
                        $predicate++;
                        $predicate_bloc[] = $point_word;
                    } else {
                        if ($point_word->ps == 'predicate' && $infinitive) {
                            $infinitive_blocs[] = $infinitive_bloc;
                            $infinitive = 0;
                            $predicate++;
                            $predicate_bloc = array();
                            $predicate_bloc[] = $point_word;
                        }
                    }
                }
            }
        }
        if ($infinitive) {
            $infinitive_blocs[] = $infinitive_bloc;
        } else {
            $predicate_blocs[] = $predicate_bloc;
        }
        return array('infinitive_block' => $infinitive_blocs, 'predicate_block' => $predicate_blocs);
    }

    /**
     * @brief Проверка слова в блоке
     * @param $key_word - индекс слова в предложении
     * @param $block - блок
     * @return $blocs возвращает булево значение true, если слово есть в блоке, иначе false
     */

    protected function is_word_block($key_word, $block)
    {
        foreach ($block as $bl) {
            if ($bl->kw == $key_word) {
                return true;
            }
        }
        return false;
    }
}

?>
