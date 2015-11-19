<?php

namespace Aot\MivarTextSemantic\SyntaxParser\SyntaxRules;
use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;


/**
 *
 * class sub1_pre
 *
 * @brief Класс для поиска подлежащего и сказуемого в простом предложении
 * @author Елисеев Д.В.
 *
 */
class sub1_pre extends SyntaxRule
{

    protected $subject_predicate;
    /**< Найденные подлежащие и сказуемые */
    public $similar_filter = false;
    /**< однородные члены найдены */

    /**
     * @brief Метод анализа предложения на возможность запуска правила
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */

    public function analyze($wdw)
    {
        $result = false;
        $this->error = "";
        //if ($this->train_system_mode)
        //	$this->syntax_db->clear_points();
        $this->wdw = $wdw;
        //$this->create_kw_index($wdw);
        $this->sence_filter = $this->similar_filter = false;
        $this->find_points_wdw = $this->find_morph_subject_predicate($wdw);
        if (isset($this->subject_predicate['subject'], $this->subject_predicate['predicate'])) {
            $result = true;
        }
        //$this->view($this->subject_predicate);
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */

    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();

        // нет однородных подлежащих

        if (isset($this->subject_predicate['subject'], $this->subject_predicate['predicate']) &&
            count($this->subject_predicate['subject']) == 1 && count($this->subject_predicate['predicate']) >= 1
        ) {
            $this->set_sub1_pre();
        } // есть однородные подлежащие

        else {
            if (!$this->train_system_mode && isset($this->subject_predicate['subject'], $this->subject_predicate['predicate']) &&
                count($this->subject_predicate['subject']) > 1 && count($this->subject_predicate['predicate']) >= 1
            ) {
                $this->set_sub_pre();
            }
        }
        //$this->view($this->subject_predicate);
        return $this->syntax_model_rule;
    }

    /**
     * @brief Связываение одного подлежащего с несколькими однородными сказуемымы
     */

    protected function set_sub1_pre()
    {
        $uuid = uniqid();
        foreach ($this->subject_predicate['subject'] as $sub_key => $sub_word) {
            foreach ($this->subject_predicate['predicate'] as $pre_key => $pre_word) {
                //$this->view($sub_word);
                if ($sub_word->kw != $pre_word->kw) {
                    $res = $this->combination_subject_predicate($sub_word, $pre_word);
                    if ($res) {
                        //$this->view($this->train_system_mode);
                        $pre_word->O = $sub_word->O = 'subject_predicate';
                        $pre_word->Oz = $sub_word->Oz = $uuid;
                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $sub_word, $pre_word,
                            $uuid);
                        if ($this->train_system_mode && !$sub_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID,
                                null, null)
                        ) {
                            $this->syntax_db->add_syntax_relation($sub_word->dw->initial_form,
                                $pre_word->dw->initial_form,
                                UUD_\Aot\MivarTextSemantic\SyntaxParser\Constants::BASIS_MIVAR,
                                \Aot\MivarTextSemantic\Constants::BASIS_MIVAR);
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @brief Связываение нескольких однородных или нет подлежащих с несколькими однородными сказуемымы
     */

    protected function set_sub_pre()
    {
        $blocs = $this->subjects_predicates_blocs($this->find_points_wdw);
        //$this->view($blocs);
        $subject_block = null;
        $subject_count_dw = null;
        $error = false;

        // Ищем блок, в котором точно есть подлежащее и подсчитываем статистику в каждом блоке

        if (isset($blocs['subject_block']) && is_array($blocs['subject_block'])) {
            foreach ($this->subject_predicate['subject'] as $sub_key => $sub_word) {
                foreach ($blocs['subject_block'] as $key_block => $block) {
                    //  в одном ли блоке
                    if ($this->is_word_block($sub_word->kw, $block)) {
                        $sub_word->parameters['key_block'] = $key_block;
                        $sub_word->parameters['similar_parts'] = count($block);
                        //$this->find_points_wdw[$sub_key]->parameters['key_block'] = $key_block;
                        //$this->find_points_wdw[$sub_key]->parameters['similar_parts'] = count($block);
                        if ($subject_count_dw === null || $subject_count_dw > $sub_word->count_dw) {
                            $subject_count_dw = $sub_word->count_dw;
                        }
                        break;
                    }
                }
                if ($sub_word->count_dw == 1 && $subject_block === null) {
                    $subject_block = $sub_word->parameters['key_block'];
                } else {
                    if ($sub_word->count_dw == 1 && $subject_block != $sub_word->parameters['key_block']) {
                        $error = true;
                        $this->error = 'Разбросанные подлежащие!';
                    }
                }
            }
        }
        //$this->view($this->subject_predicate['subject']);
        // Проверяем согласованность подлежащих и сказыемых и записываем связь

        $pre_subject_block = array();
        $pre_subjects = array();
        $pre_predicates = array();
        $uuid = uniqid();
        foreach ($this->subject_predicate['subject'] as $sub_key => $sub_word) {
            foreach ($this->subject_predicate['predicate'] as $pre_key => $pre_word) {
                if ($sub_word->kw != $pre_word->kw) {
                    $res = false;
                    if ($subject_block === $sub_word->parameters['key_block']) {
                        if ($sub_word->parameters['similar_parts'] > 1) {
                            // проверка множественного числа у сказуемого
                            if ($pre_word->dw->check_parameter(null, \Aot\MivarTextSemantic\Constants::NUMBER_ID,
                                \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID)
                            ) {
                                $res = true;
                            } //$res = self::combination_subject_predicate($sub_word, $pre_word);
                            else {
                                if ($subject_count_dw == $sub_word->count_dw) {
                                    $res = $this->combination_subject_predicate($sub_word, $pre_word);
                                }
                            }
                        } else {
                            if (!$res = $this->combination_subject_predicate($sub_word, $pre_word)) {
                                //$error = true;
                                //$sentence['error'] = 'Несогласуется подлежащие и сказуемое!';
                                continue;
                            }
                        }
                    } //$subject_block == null когда не определился блок с подлежащим однозначно

                    else {
                        if ($subject_block === null) {
                            //dpm($sub_word);
                            $res = $this->combination_subject_predicate($sub_word, $pre_word);
                        }
                    }
                    if ($res && ($subject_block === null || ($subject_block == $sub_word->parameters['key_block']))) {
                        $pre_word->O = $sub_word->O = 'subject_predicate';
                        $pre_word->Oz = $sub_word->Oz = $uuid;

                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $sub_word, $pre_word,
                            $uuid);

                        // Если связываем подлежащие из разных блоков необходим смысловой фильтр

                        $pre_subject_block[$sub_word->parameters['key_block']] = $sub_word->parameters['key_block'];
                        if (count($pre_subject_block) > 1) {
                            $this->sence_filter = true;
                        }

                        // если есть несколько связанных подлежащих в одном блоке - то проверка однородных членов

                        $pre_subjects[$sub_word->kw] = $sub_word->kw;
                        $pre_predicates[$pre_word->kw] = $pre_word->kw;
                        if (count($pre_subjects) > 1 || count($pre_predicates) > 1) {
                            $this->similar_filter = true;
                        }
                    }
                }
            }
        }

        //$this->view($blocs);
        return $this;
    }

    /**
     * @brief Согласование подлежащего и сказуемого
     * @param $sub_word - слово подлежащее
     * @param $pre_word - слово сказуемое
     * @return - возвращает булево значение true если существительное сочетается с глаголом иначе false
     */

    protected function combination_subject_predicate($sub_word, $pre_word)
    {
        $res = false;

        // существительное и глагол в третьем лице
        if ($sub_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID && $pre_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID &&
            (!isset($pre_word->dw->parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID]) || in_array(\Aot\MivarTextSemantic\Constants::PERSON_THIRD_ID,
                    $pre_word->dw->parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID]->id_value_attr))
        ) {
            // проверка совпадения рода и числа
            $res = $this->compare_parameters($sub_word->dw, $pre_word->dw,
                array(\Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::GENUS_ID));
        } // местоимение и глагол в нужном лице
        else {
            if ($sub_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID && $pre_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID) {
                $res = $this->compare_parameters($sub_word->dw, $pre_word->dw, array(
                    \Aot\MivarTextSemantic\Constants::NUMBER_ID,
                    \Aot\MivarTextSemantic\Constants::PERSON_ID,
                    \Aot\MivarTextSemantic\Constants::GENUS_ID
                ));
            }
        }

        return $res;
    }

    /**
     * @brief Метод смысловой фильтрации полученных результатов (Если необходимо)
     */

    public function sence_filter()
    {

    }

    /**
     * @brief Поиск главных членов предложения (существительных, местоимений и глаголов)
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     * @return $sentence_space_SP - выделенное пространство
     */

    protected function find_morph_subject_predicate($sentence_space)
    {
        $sentence_space_SP = array();
        $this->subject_predicate = array();
        foreach ($sentence_space->get_space() as $key => $point_word) {
            //$this->view($point_word);
            // проверяем слово на имя существительное в именительном падеже
            // проверяем слово на местоимение в именительном падеже

            if (($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                        \Aot\MivarTextSemantic\Constants::CASE_ID,
                        \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID) ||
                    ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID,
                            \Aot\MivarTextSemantic\Constants::CASE_ID,
                            \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                        && !$point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID,
                            \Aot\MivarTextSemantic\Constants::TYPE_OF_PRONOUN_ID,
                            \Aot\MivarTextSemantic\Constants::PRONOUN_ADJECTIVE_ID))) &&
                !$this->find_ps_prev($point_word->kw - 1, array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID),
                    array(
                        \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
                        \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                        \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID
                    ))
            ) {
                $point_word->ps = 'subject';
                $sentence_space_SP[$key] = $point_word;

            }

            // проверяем слово на глагол

            if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, null,
                    null) && ($point_word->count_dw == 1 ||
                    !$this->find_ps_prev($point_word->kw - 1,
                        array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID), array(
                            \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID,
                            \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID,
                            \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID
                        )))
            ) {

                // инфинитив или нет
                if (in_array(mb_substr($point_word->w->word, -2, 2, 'utf-8'), array('ть', 'чь')) ||
                    in_array(mb_substr($point_word->w->word, -4, 4, 'utf-8'), array('ться', 'чься'))
                ) {
                    continue;
                }

                $point_word->ps = 'predicate';
                $sentence_space_SP[$key] = $point_word;
            }
            if (isset($point_word->ps)) {
                $this->subject_predicate[$point_word->ps][$key] = $point_word;
            }
        }
        //$this->view($this->subject_predicate);
        return $sentence_space_SP;
    }

    /**
     * @brief Выделение однородных блоков
     * @param $find_points_wdw - пространство отфильтрованных слов и их морфологических признаков
     * @return $blocs найденные однородные блоки
     */

    protected function subjects_predicates_blocs($find_points_wdw)
    {
        //$this->view($find_points_wdw);
        $subject = 0;
        $predicate = 0;
        $subject_blocs = array();
        $predicate_blocs = array();
        foreach ($find_points_wdw as $point_word) {
            if (isset($point_word->ps) && $point_word->ps == 'subject' && !$predicate) {
                $subject++;
                $subject_bloc[] = $point_word;
            } else {
                if (isset($point_word->ps) && $point_word->ps == 'subject' && $predicate) {
                    $predicate_blocs[] = $predicate_bloc;
                    $predicate = 0;
                    $subject++;
                    $subject_bloc = array();
                    $subject_bloc[] = $point_word;
                } else {
                    if (isset($point_word->ps) && $point_word->ps == 'predicate' && !$subject) {
                        $predicate++;
                        $predicate_bloc[] = $point_word;
                    } else {
                        if (isset($point_word->ps) && $point_word->ps == 'predicate' && $subject) {
                            $subject_blocs[] = $subject_bloc;
                            $subject = 0;
                            $predicate++;
                            $predicate_bloc = array();
                            $predicate_bloc[] = $point_word;
                        }
                    }
                }
            }
        }
        if ($subject) {
            $subject_blocs[] = $subject_bloc;
        } else {
            $predicate_blocs[] = $predicate_bloc;
        }
        return array('subject_block' => $subject_blocs, 'predicate_block' => $predicate_blocs);
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
