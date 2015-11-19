<?php

namespace Aot\MivarTextSemantic\SyntaxParser\SyntaxRules;

use Aot\MivarTextSemantic\MivarSpaceWdwOOz;
use Aot\MivarTextSemantic\SyntaxParser\SyntaxRule;

/**
 *
 * class adjective4noun
 *
 * @brief Класс для поиска прилагательных к существительному
 *
 */
class adjective4noun extends SyntaxRule
{

    /**
     * @brief Метод анализа предложения на возможность запуска правила
     * @detailed Попутно формируется массив: $this->find_points_wdw в формате array('adj' => $, 'noun' => $), где
     * adj - массив прилагательных к существительному,
     * noun - существительное
     * @param $wdw - пространство слов и их морфологических признаков wdw
     * @return возвращает булево значение true в случае возможности выполнения правила, иначе false
     */
    public function analyze($wdw)
    {
        $result = false;
        $this->error = "";
        $hypothesis = array();
        $adjArray = array();
        foreach ($wdw->get_space() as $wdw_key => $one_wdw) {
            $wordIsPunctuationMark = in_array($one_wdw->w->word, array(','));
            if ($one_wdw->dw->check_parameter(\Aot\MivarTextSemantic\Constants::ADJECTIVE_CLASS_ID) || $wordIsPunctuationMark) {
                if (!$wordIsPunctuationMark) {
                    $adjArray[] = $one_wdw;
                }
            } else {
                if ($one_wdw->dw->check_parameter(\Aot\MivarTextSemantic\Constants::NOUN_CLASS_ID)) {
                    $hypothesis[] = array('adj' => $adjArray, 'noun' => $one_wdw);
                    $adjArray = array();
                } else {
                    $adjArray = array();
                }
            }
        }

        if (count($hypothesis) > 0) {
            /* Найдены точки для обработки правилом */
            $this->find_points_wdw = $hypothesis;
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
        foreach ($this->find_points_wdw as $hypothesis) {
            foreach ($hypothesis['adj'] as $oneAdjective) {
                $this->checkAndAdd($oneAdjective, $hypothesis['noun']);
            }
        }
        return $this->syntax_model_rule;
    }

    private function checkAndAdd($adj, $noun)
    {
        //if( $this->compare_parameters($adj['dw'], $noun['dw'], array(\Aot\MivarTextSemantic\SyntaxParser\Constants::NUMBER_ID, \Aot\MivarTextSemantic\SyntaxParser\Constants::GENUS_ID, \Aot\MivarTextSemantic\SyntaxParser\Constants::CASE_ID)) ) {
        $uuid = uniqid();
        $adj->O = $noun->O = 'attribute_noun';
        $adj->Oz = $noun->Oz = $uuid;
        $this->syntax_model_rule = $this->set_point_rel($this->syntax_model_rule,
            $noun,
            $adj,
            $uuid);
        //}
    }

    public function sence_filter()
    {

    }
}