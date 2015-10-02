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
     */
    public function run(array $sviazi)
    {
        ///Проверяем все связи на то, что они являются связями
        foreach ($sviazi as $sviaz) {
            assert(is_a($sviaz, \Aot\Sviaz\Podchinitrelnaya\Base::class), true);
        }

        //Количество связей должно быть не менее двух, а то фильтру фильтровать будет нечего
        if (count($sviazi) < 2) {
            return $sviazi;
        }

        //Берём главное слово из первой связи. Т.к. не важно из какой связи брать слово,
        // ведь все связи связывают два одних и тех же слова,
        // просто по разным правилам и в разные стороны
        $main = $sviazi[0]->getMainSequenceMember();
        //Необходима проверка, т.к. далее мы берём текст слова. Не будет проверки, придёт какая-нибудь запятая и всё полетит
        if (!$main instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return $sviazi;
        }

        //Берём зависимое слово из первой связи. Т.к. не важно из какой связи брать слово,
        // ведь все связи связывают два одних и тех же слова,
        // просто по разным правилам и в разные стороны
        $depended = $sviazi[0]->getDependedSequenceMember();
        //Необходима проверка, т.к. далее мы берём текст слова. Не будет проверки, придёт какая-нибудь запятая и всё полетит
        if (!$depended instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return $sviazi;
        }

        //Подкачиваем текст
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

        $intersection_viazey=$this->intersectSviazeyAndRulesFromDB($sviazi, $syntax_rules);
        if (empty($intersection_viazey)) {
            //возвращаем те связи, которые попали на вход фильтра
            return $sviazi;
        }
        return $intersection_viazey;


        //ПРошлая версия, вызывающая генерацию связей
//        if (count($syntax_rules) > 1) {
//            //возвращаем те связи, которые попали на вход фильтра
//            return $sviazi;
//        }
//
//        //Связи, на основе правил, полученных из БД
//        //Если всё ок, то в массиве лишь одно правило
//        $syntax_rule = $syntax_rules[0];
//
//        //возвращаем созданную связь
//        $created_sviaz = $this->createSviazFromSyntaxRule($sviazi[0], $syntax_rule);
//        if (is_null($created_sviaz)) {
//            return $sviazi;
//        }
//        return [$created_sviaz];
    }


    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @param \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected function intersectSviazeyAndRulesFromDB($sviazi, $syntax_rules)
    {
        $intersection_viazey=[];
        foreach($sviazi as $sviaz)
        {
            $sviaz_rule_id=$sviaz->getRule()->getDao()->getId();
            /* @var \Aot\Sviaz\SequenceMember\Word\Base $main_member*/
            $main_member=$sviaz->getMainSequenceMember();
            $sviaz_main=$main_member->getSlovo()->getInitialForm();
            /* @var \Aot\Sviaz\SequenceMember\Word\Base $depended_member*/
            $depended_member=$sviaz->getDependedSequenceMember();
            $sviaz_depended=$depended_member->getSlovo()->getInitialForm();
            foreach ($syntax_rules as $syntax_rule) {
//                print_r(
//                    [
//                        ['sviaz_id'=>$sviaz_rule_id,'rule_id'=>$syntax_rule->getId()],
//                        ['sviaz_main'=>$sviaz_main,'rule_main'=>$syntax_rule->getMain()->getName()],
//                        ['sviaz_depended'=>$sviaz_depended,'rule_depended'=>$syntax_rule->getDepend()->getName()]
//                    ]
//                );
                if ($sviaz_rule_id!==$syntax_rule->getId())
                    continue;
                if ($sviaz_main!==$syntax_rule->getMain()->getName())
                    continue;
                if ($sviaz_depended!==$syntax_rule->getDepend()->getName())
                    continue;
                $intersection_viazey[]=$sviaz;
                break;
            }

        }
        return $intersection_viazey;
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

/*
 * НА БУДУЩЕЕ!!! ВЫНЕСТИ В ДРУГОЙ МЕТОД
 *
 *
 * //оббегаем все связи и сравниваем их
    foreach ($sviazi as $sviaz) {

        //Если Id совпадают, значит перед нами таже самая связь, пропускаем её
        if ($sviaz_from_sequence->getId() === $id_sviaz) {
            continue;
        }
        //Если цикл дошёл сюда, значит это другая связь, нужно проверить, есть ли конфликт
        $main_member_sequence = $sviaz_from_sequence->getMainSequenceMember();
        $depended_member_sequence = $sviaz_from_sequence->getDependedSequenceMember();
        //Если связь повторяется (не важно в какую сторону она будет направлена) нужно идти в БД и вытаскивать связь от туда
        if (
            ($main_member_sequence === $main_member_sviaz && $depended_member_sequence === $depended_member_sviaz)
            ||
            ($main_member_sequence === $depended_member_sviaz && $depended_member_sequence === $main_member_sviaz)
        ) {
            // получаем начальные формы слов
            $member1_initial_form = $main_member_sviaz->getSlovo()->getInitialForm();
            $member2_initial_form = $depended_member_sviaz->getSlovo()->getInitialForm();

            //Создаём слова
            $word1 = new \SemanticPersistence\Entities\SemanticEntities\Word;
            $word2 = new \SemanticPersistence\Entities\SemanticEntities\Word;
            $word1->setName($member1_initial_form);
            $word2->setName($member2_initial_form);

            //Ищем в БД, есть ли связь
            \Aot\Main::findSviazBetweenTwoWords($word1, $word2);
        }

    }
*/
