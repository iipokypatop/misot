<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 30.09.2015
 * Time: 15:35
 */

namespace Aot\Sviaz\Podchinitrelnaya\Filters;

class Syntax
{
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {

    }

    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     *
     * Примечание: для поиска в БД правил используются слова, взятые из первой связи.
     */
    public function run(array $sviazi)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class), true);
        }

        //Количество связей должно быть не менее двух, а то фильтру фильтровать будет нечего
        if (count($sviazi) < 2) {
            return $sviazi;
        }

        $main = $sviazi[0]->getMainSequenceMember();
        if (!$main instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return $sviazi;
        }

        $depended = $sviazi[0]->getDependedSequenceMember();
        if (!$depended instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return $sviazi;
        }

        $text1 = $main->getSlovo()->getInitialForm();
        $text2 = $depended->getSlovo()->getInitialForm();

        //Ищем в БД, есть ли связи, если будет несколько, вернём их все
        /** @var \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules */
        $syntax_rules = \Aot\Main::findSviazBetweenTwoWords($text1, $text2);

        //в случае неудачи (null)
        if (is_null($syntax_rules)) {
            //возвращаем те связи, которые попали на вход фильтра
            return $sviazi;
        }

        $intersection_sviazey = $this->intersectSviazeyAndRulesFromDB($sviazi, $syntax_rules);
        if (empty($intersection_sviazey)) {
            //возвращаем те связи, которые попали на вход фильтра
            return $sviazi;
        }
        return $intersection_sviazey;
    }


    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @param \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected function intersectSviazeyAndRulesFromDB(array $sviazi, array $syntax_rules)
    {
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class), true);
        }

        foreach ($syntax_rules as $syntax_rule) {
            assert(is_a($syntax_rule, \SemanticPersistence\Entities\SemanticEntities\SyntaxRule::class), true);
        }

        $intersection_viazey = [];
        $array_of_rules_in_sviazah = [];
        foreach ($sviazi as $sviaz) {
            $sviaz_rule_id = $sviaz->getRule()->getDao()->getId();
            if ($sviaz_rule_id === null) {
                continue;
            }
            /* @var \Aot\Sviaz\SequenceMember\Word\Base $main_member */
            $main_member = $sviaz->getMainSequenceMember();
            /* @var \Aot\Sviaz\SequenceMember\Word\Base $depended_member */
            $depended_member = $sviaz->getDependedSequenceMember();
            $key = $this->createKey(
                $sviaz_rule_id,
                $main_member->getSlovo()->getInitialForm(),
                $depended_member->getSlovo()->getInitialForm()
            );
            $array_of_rules_in_sviazah[$key] = $sviaz;
        }

        foreach ($syntax_rules as $syntax_rule) {
            $key = $this->createKey(
                $syntax_rule->getId(),
                $syntax_rule->getMain()->getName(),
                $syntax_rule->getDepend()->getName()
            );
            if (array_key_exists($key, $array_of_rules_in_sviazah)) {
                $intersection_viazey[] = $array_of_rules_in_sviazah[$key];
            }
        }
        return
            $intersection_viazey;
    }


    /**
     * @param string $id
     * @param string $main
     * @param string $depended
     * @return string
     */
    protected function createKey($id, $main, $depended)
    {
        assert(is_string($id));
        assert(is_string($main));
        assert(is_string($depended));
        return join("_", [
            $id,
            $main,
            $depended,
        ]);
    }


    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base $sviaz
     * @param \SemanticPersistence\Entities\SemanticEntities\SyntaxRule $syntax_rule
     * @return \Aot\Sviaz\Podchinitrelnaya\Base | null
     */
    protected function createSviazFromSyntaxRule($sviaz, $syntax_rule)
    {
        /** @var \Aot\Sviaz\SequenceMember\Word\Base $main */
        $main = $sviaz->getMainSequenceMember();

        /** @var \Aot\Sviaz\SequenceMember\Word\Base $depended */
        $depended = $sviaz->getDependedSequenceMember();


        //Прямой порядок слов
        if ($syntax_rule->getMain()->getName() === $main->getSlovo()->getInitialForm()) {
            $main_sequence_member = $main;
            $depended_sequence_member = $depended;
        } //Обратный порядок слов
        elseif ($syntax_rule->getMain()->getName() === $depended->getSlovo()->getInitialForm()) {
            $main_sequence_member = $depended;
            $depended_sequence_member = $main;
        } else {
            return null;
        }

        $rule = \Aot\Sviaz\Rule\Base::createByDao($syntax_rule->getRuleConfig());
        $sequence = $sviaz->getSequence();
        /** @var \Aot\Sviaz\SequenceMember\Word\Base $main_sequence_member */
        /** @var \Aot\Sviaz\SequenceMember\Word\Base $depended_sequence_member */
        //Создаём и добавляем саму связь
        $sviaz = \Aot\Sviaz\Podchinitrelnaya\Factory::get()->build(
            $main_sequence_member,
            $depended_sequence_member,
            $rule,
            $sequence
        );
        return $sviaz;
    }


}