<?php

namespace Aot\MivarTextSemantic\SyntaxParser\TeacherSyntaxRules;
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
class sub_pre_auto extends SyntaxRule
{

    protected $points_to_agreement;
    /**< Найденные точки для согласования */
    public $similar_filter = false;
    /**< однородные члены найдены */

    /**< Граф переходов из одного состояний в другое */

    protected $states = array('state_0' => array('state' => 'state_0',
        'jumps' => array('cond_subject' => 'state_agreement_predicate',
            'cond_predicate' => 'state_agreement_subject')),
        'state_subject' => array('state' => 'state_subject',
            'jumps' => array('cond_subject' => 'state_subject',
                'cond_predicate' => 'state_agreement_subject')),
        'state_predicate' => array('state' => 'state_predicate',
            'jumps' => array('cond_predicate' => 'state_predicate',
                'cond_subject' => 'state_agreement_predicate')),
        'state_agreement_subject' => array('state' => 'state_agreement_subject',
            'jumps' => array('cond_empty' => 'state_predicate')),
        'state_agreement_predicate' => array('state' => 'state_agreement_predicate',
            'jumps' => array('cond_empty' => 'state_subject')),
        'state_finish' => array('state' => 'state_finish',
            'finish_states' => array('state_subject', 'state_predicate')));

    protected $current_state = null;
    /**< текущее состояние */
    protected $cash_main = array();
    /**< Кэш главных слов */
    protected $cash_depend = array();
    /**< Кэш зависимых слов */
    protected $cash_main_current = array();
    /**< Кэш текущих главных слов для согласования */
    protected $cash_depend_current = array();
    /**< Кэш текущих зависимых слов для согласования */

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
        $this->sence_filter = $this->similar_filter = false;
        $this->find_points_wdw = $this->find_morph_points_to_agreement($wdw);
        if (isset($this->points_to_agreement['main'], $this->points_to_agreement['depend']))
            $result = true;
        //$this->view($this->find_points_wdw );
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */

    public function perfom1()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();

        /*
        // нет однородных подлежащих

        if (isset($this->subject_predicate['subject'], $this->subject_predicate['predicate']) &&
            count($this->subject_predicate['subject']) == 1 && count($this->subject_predicate['predicate']) >= 1) {
            $this->set_sub1_pre();
        }

        // есть однородные подлежащие

        else if (!$this->train_system_mode && isset($this->subject_predicate['subject'], $this->subject_predicate['predicate']) &&
            count($this->subject_predicate['subject']) > 1 && count($this->subject_predicate['predicate']) >= 1) {
            $this->set_sub_pre();
        }
        //$this->view($this->subject_predicate); */
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
                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $sub_word, $pre_word, $uuid);
                        if ($this->train_system_mode && !$sub_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID, null, null)) {
                            $this->syntax_db->add_syntax_relation($sub_word->dw->initial_form, $pre_word->dw->initial_form, UUD_\Aot\MivarTextSemantic\SyntaxParser\Constants::BASIS_MIVAR, \Aot\MivarTextSemantic\Constants::BASIS_MIVAR);
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
                        if ($subject_count_dw === null || $subject_count_dw > $sub_word->count_dw)
                            $subject_count_dw = $sub_word->count_dw;
                        break;
                    }
                }
                if ($sub_word->count_dw == 1 && $subject_block === null) {
                    $subject_block = $sub_word->parameters['key_block'];
                } else if ($sub_word->count_dw == 1 && $subject_block != $sub_word->parameters['key_block']) {
                    $error = true;
                    $this->error = 'Разбросанные подлежащие!';
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
                            if ($pre_word->dw->check_parameter(null, \Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID))
                                $res = true;
                            //$res = self::combination_subject_predicate($sub_word, $pre_word);
                            else if ($subject_count_dw == $sub_word->count_dw) {
                                $res = $this->combination_subject_predicate($sub_word, $pre_word);
                            }
                        } else if (!$res = $this->combination_subject_predicate($sub_word, $pre_word)) {
                            //$error = true;
                            //$sentence['error'] = 'Несогласуется подлежащие и сказуемое!';
                            continue;
                        }
                    } //$subject_block == null когда не определился блок с подлежащим однозначно

                    else if ($subject_block === null) {
                        //dpm($sub_word);
                        $res = $this->combination_subject_predicate($sub_word, $pre_word);
                    }
                    if ($res && ($subject_block === null || ($subject_block == $sub_word->parameters['key_block']))) {
                        $pre_word->O = $sub_word->O = 'subject_predicate';
                        $pre_word->Oz = $sub_word->Oz = $uuid;

                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $sub_word, $pre_word, $uuid);

                        // Если связываем подлежащие из разных блоков необходим смысловой фильтр

                        $pre_subject_block[$sub_word->parameters['key_block']] = $sub_word->parameters['key_block'];
                        if (count($pre_subject_block) > 1)
                            $this->sence_filter = true;

                        // если есть несколько связанных подлежащих в одном блоке - то проверка однородных членов

                        $pre_subjects[$sub_word->kw] = $sub_word->kw;
                        $pre_predicates[$pre_word->kw] = $pre_word->kw;
                        if (count($pre_subjects) > 1 || count($pre_predicates) > 1)
                            $this->similar_filter = true;
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
            (!isset($pre_word->dw->parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID]) || in_array(\Aot\MivarTextSemantic\Constants::PERSON_THIRD_ID, $pre_word->dw->parameters[\Aot\MivarTextSemantic\Constants::PERSON_ID]->id_value_attr))
        ) {
            // проверка совпадения рода и числа
            $res = $this->compare_parameters($sub_word->dw, $pre_word->dw, array(\Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::GENUS_ID));
        } // местоимение и глагол в нужном лице
        else if ($sub_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID && $pre_word->dw->id_word_class == \Aot\MivarTextSemantic\Constants::VERB_CLASS_ID) {
            $res = $this->compare_parameters($sub_word->dw, $pre_word->dw, array(\Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::PERSON_ID, \Aot\MivarTextSemantic\Constants::GENUS_ID));
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

    protected function find_morph_points_to_agreement($sentence_space)
    {
        $sentence_space_SP = array();
        $this->points_to_agreement = array();
        foreach ($sentence_space->get_space() as $key => $point_word) {
            //$this->view($point_word);
            // проверяем слово на имя существительное в именительном падеже
            // проверяем слово на местоимение в именительном падеже

            if (($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID, \Aot\MivarTextSemantic\Constants::CASE_ID, \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID) ||
                    ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID, \Aot\MivarTextSemantic\Constants::CASE_ID, \Aot\MivarTextSemantic\Constants::CASE_SUBJECTIVE_ID)
                        && !$point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID, \Aot\MivarTextSemantic\Constants::TYPE_OF_PRONOUN_ID, \Aot\MivarTextSemantic\Constants::PRONOUN_ADJECTIVE_ID))) &&
                !$this->find_ps_prev($point_word->kw - 1, array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID), array(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID, \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID))
            ) {
                $point_word->ps = 'main';
                $sentence_space_SP[] = $point_word;

            }

            // проверяем слово на глагол

            if ($point_word->dw->check_parameter(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, null, null) && ($point_word->count_dw == 1 ||
                    !$this->find_ps_prev($point_word->kw - 1, array(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID), array(\Aot\MivarTextSemantic\Constants::VERB_CLASS_ID, \Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID, \Aot\MivarTextSemantic\Constants::PRONOUN_CLASS_ID)))
            ) {

                // инфинитив или нет
                if (in_array(mb_substr($point_word->w->word, -2, 2, 'utf-8'), array('ть', 'чь')) ||
                    in_array(mb_substr($point_word->w->word, -4, 4, 'utf-8'), array('ться', 'чься'))
                )
                    continue;

                $point_word->ps = 'depend';
                $sentence_space_SP[] = $point_word;
            }
            if (isset($point_word->ps))
                $this->points_to_agreement[$point_word->ps][$key] = $point_word;
        }
        return $sentence_space_SP;
    }

    /**
     * @brief Функция обхода графа
     * @param $sentence_space - пространство слов и их морфологических признаков wdw
     */

    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();

        $this->current_state = 'state_0';
        $this->{$this->current_state}();

        // флаг правильности выполнения разбора по правилу

        $flag_complete = true;
        for ($i = 0; $i <= count($this->find_points_wdw); $i++) {

            // флаг проверки того, что граф детерминирован

            $flag_state = false;
            $wdw = isset($this->find_points_wdw[$i]) ? $this->find_points_wdw[$i] : null;
            foreach ($this->states[$this->current_state]['jumps'] as $cond => $new_state) {
                if ($this->$cond($wdw)) {
                    if ($cond == 'cond_empty') {
                        //$this->view($i);
                        $i--;
                        $wdw = isset($this->find_points_wdw[$i]) ? $this->find_points_wdw[$i] : null;
                        //$this->view($wdw);
                    }
                    $this->current_state = $new_state;
                    $this->{$this->current_state}($wdw);
                    $flag_state = true;
                    /*echo "<pre>";
                    echo $new_state." - ".$wdw->w->word." - ".$i;
                    echo "</pre>";
                    break;*/
                }
            }
            if (!$flag_state) {
                $flag_complete = false;
                break;
            }
        }
        if (in_array($this->current_state, $this->states['state_finish']['finish_states']))
            $this->state_finish();
        //$this->view($this->find_points_wdw);
        return $this->syntax_model_rule;
    }

    /**
     * @brief Метод для состояния state_0
     * @param $wdw - точка wdw
     */

    protected function state_0($wdw = null)
    {
        $this->cash_main = $this->cash_main_current = array();
        $this->cash_depend = $this->cash_depend_current = array();
    }

    /**
     * @brief Метод для проверки условия cond_subject
     * @param $wdw - точка wdw
     * @return true - если условие сработало, false - иначе
     */

    protected function cond_subject($wdw)
    {
        $result = false;
        if (isset($wdw->ps) && $wdw->ps == 'main') {
            $result = true;
        }
        return $result;
    }

    /**
     * @brief Метод для проверки условия cond_predicate
     * @param $wdw - точка wdw
     * @return true - если условие сработало, false - иначе
     */

    protected function cond_predicate($wdw)
    {
        $result = false;
        if (isset($wdw->ps) && $wdw->ps == 'depend') {
            $result = true;
        }
        return $result;
    }

    /**
     * @brief Метод для проверки условия cond_predicate
     * @param $wdw - точка wdw
     * @return true - всегда (безусловный переход)
     */

    protected function cond_empty($wdw)
    {
        return true;
    }

    /**
     * @brief Метод для состояния state_agreement_predicate
     * @param $wdw - точка wdw
     */

    protected function state_agreement_predicate($wdw = null)
    {
        //$this->cash_main_current[] = $wdw;
        foreach ($this->cash_depend_current as &$depend) {
            foreach ($this->cash_main as &$mains) {

                // согласование однородных подлежащих

                if (count($mains) > 1) {
                    if ($depend->dw->check_parameter(null, \Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID)) {
                        /*echo "<pre>";
                        echo $depend->w->word;
                        echo "</pre>";*/
                        foreach ($mains as &$main) {
                            if (isset($main->Oz))
                                $uuid = $depend->Oz = $main->Oz;
                            else
                                $uuid = $main->Oz = $depend->Oz = uniqid();
                            $main->O = $depend->O = 'subject_predicate';
                            $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $main, $depend, $uuid);
                        }
                        unset($main);
                    }
                }
                foreach ($mains as &$main) {
                    if ($this->combination_subject_predicate($main, $depend)) {
                        if (isset($main->Oz)) {
                            //$this->view($wdw);
                            $uuid = $depend->Oz = $main->Oz;
                        } else
                            $uuid = $main->Oz = $depend->Oz = uniqid();
                        $main->O = $depend->O = 'subject_predicate';
                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $main, $depend, $uuid);
                    }
                }
                unset($main);
            }
            unset($mains);
        }
        unset($depend);
        $this->cash_depend = array_merge($this->cash_depend, $this->cash_depend_current);
        $this->cash_depend_current = array();
        //$this->view($this->cash_main);
    }

    /**
     * @brief Метод для состояния state_agreement_subject
     * @param $wdw - точка wdw
     */

    protected function state_agreement_subject($wdw = null)
    {

        // случай однородных подлежащих

        if (count($this->cash_main_current) > 1) {
            foreach ($this->cash_depend as &$depend) {
                if ($depend->dw->check_parameter(null, \Aot\MivarTextSemantic\Constants::NUMBER_ID, \Aot\MivarTextSemantic\Constants::NUMBER_PLURAL_ID)) {
                    foreach ($this->cash_main_current as &$main) {
                        if (isset($depend->Oz))
                            $uuid = $main->Oz = $depend->Oz;
                        else
                            $uuid = $main->Oz = $depend->Oz = uniqid();
                        $main->O = $depend->O = 'subject_predicate';
                        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $main, $depend, $uuid);
                    }
                    unset($main);
                }
            }
            unset($depend);
        }
        foreach ($this->cash_main_current as &$main) {
            foreach ($this->cash_depend as &$depend) {
                if ($this->combination_subject_predicate($main, $depend)) {
                    if (isset($depend->Oz))
                        $uuid = $main->Oz = $depend->Oz;
                    else
                        $uuid = $main->Oz = $depend->Oz = uniqid();
                    $main->O = $depend->O = 'subject_predicate';
                    $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule, $main, $depend, $uuid);
                }
            }
            unset($depend);
        }
        unset($main);
        $this->cash_main[] = $this->cash_main_current;
        $this->cash_main_current = array();
        //$this->view($this->cash_main);
    }

    /**
     * @brief Метод для состояния state_subject
     * @param $wdw - точка wdw
     */

    protected function state_subject($wdw)
    {
        $this->cash_main_current[] = $wdw;
    }

    /**
     * @brief Метод для состояния state_predicate
     * @param $wdw - точка wdw
     */

    protected function state_predicate($wdw)
    {
        $this->cash_depend_current[] = $wdw;
    }

    /**
     * @brief Метод для состояния state_finish
     * @param $wdw - точка wdw
     */

    protected function state_finish()
    {
        if ($this->cash_main_current)
            $this->state_agreement_subject();
        if ($this->cash_depend_current)
            $this->state_agreement_predicate();
    }

}

?>
