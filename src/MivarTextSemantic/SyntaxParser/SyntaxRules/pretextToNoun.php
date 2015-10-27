<?php

namespace Aot\MivarTextSemantic\SyntaxParser\SyntaxRules;
use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;

/**
 *
 * class sub1_pre
 *
 * @brief Класс для поиска подлежащего и сказуемого в простом предложении, когда 1 подлежащее и одно или несколько сказуемых
 *
 */

class pretextToNoun extends SyntaxRule
{

    public function analyze($wdw)
    {
        $result = false;
        $this->error = '';
        $pretext = null;
        $hypothesis = array();
        //$this->view($wdw);
        foreach ($wdw->get_space() as $wdw_key => $one_wdw) {

            if ($one_wdw->dw->check_parameter(\Aot\MivarTextSemantic\Constants::PREPOSITION_CLASS_ID)) {
                $pretext = $one_wdw;
            } else if ($one_wdw->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID) && (!is_null($pretext))) {
                $hypothesis[] = array('pretext' => $pretext, 'noun' => $one_wdw);
                $pretext = NULL;
            } else if (in_array($one_wdw->w->word, array(','))) {
                $pretext = NULL;
            }
        }
        if (count($hypothesis) > 0) {
            // 	 Найдены точки для обработки правилом
            $this->find_points_wdw = $hypothesis;
            $result = true;
        }
        //$this->view($result);
        return $result;
    }

    /**
     * @brief Метод выполнения правила
     */
    public function perfom()
    {
        $this->syntax_model_rule = new MivarSpaceWdwOOz();
        foreach ($this->find_points_wdw as $hypothesis) {
            $this->checkAndAdd($hypothesis['pretext'], $hypothesis['noun']);
        }
        //$this->view($this->syntax_model_rule);
        return $this->syntax_model_rule;
    }

    private function checkAndAdd($first, $second)
    {
        //if( $this->compare_parameters($first['dw'], $second['dw'], array(\Aot\MivarTextSemantic\SyntaxParser\Constants::NUMBER_ID, \Aot\MivarTextSemantic\SyntaxParser\Constants::GENUS_ID, \Aot\MivarTextSemantic\SyntaxParser\Constants::CASE_ID)) ) {
        $uuid = uniqid();
        $first->O = $second->O = "prepositional_phrase";
        $first->Oz = $second->Oz = $uuid;
        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule,
            $first,
            $second,
            $uuid);
        //}
    }

    public function sence_filter()
    {

    }
}









