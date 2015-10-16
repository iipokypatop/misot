<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.10.2015
 * Time: 11:10
 */

namespace Aot\Sviaz\PreProcessors;


class HomogeneitySupposed extends \Aot\Sviaz\PreProcessors\Base
{
    /**
     * @brief На данный момент - заглушечка, а должен быть реальный метод, создающий гипотезы о гомогенных группах
     *
     * @param \Aot\Sviaz\Sequence $raw_sequence
     * @return \Aot\Sviaz\Sequence
     */
    public function run(\Aot\Sviaz\Sequence $raw_sequence)
    {
        /*
         * ТЕСТОВЫЕ ДАННЫЕ ДЛЯ ОДНОРОДНОСТЕЙ!!!
         *
        */

        // todo сделать данный метод!
        /*
        $member6=$raw_sequence->getMemberByPosition(1);
        $member7=$raw_sequence->getMemberByPosition(2);
        $part3=\Aot\Sviaz\HomogeneitySupposed::create();
        $part3->addMember($member6);
        $part3->addMember($member7);
        $raw_sequence->addHypothesisSupposed($part3);
        print_r("\n".$member6->getSlovo()->getText().' '.$member7->getSlovo()->getText()."\n");


        //$member1=$raw_sequence->getMemberByPosition(4);
        $member2=$raw_sequence->getMemberByPosition(2);
        $member3=$raw_sequence->getMemberByPosition(3);
        $member4=$raw_sequence->getMemberByPosition(5);
        $member5=$raw_sequence->getMemberByPosition(6);

        $part1=\Aot\Sviaz\HomogeneitySupposed::create();
        //$part1->addMember($member1);
        $part1->addMember($member2);
        $part1->addMember($member3);
        $part1->addMember($member4);
        $part1->addMember($member5);
        $raw_sequence->addHypothesisSupposed($part1);
        print_r("\n".$member2->getSlovo()->getText().' '.$member3->getSlovo()->getText().' '.$member4->getSlovo()->getText().' '.$member5->getSlovo()->getText()."\n");


        $member4=$raw_sequence->getMemberByPosition(7);
        $member5=$raw_sequence->getMemberByPosition(9);
        $part2=\Aot\Sviaz\HomogeneitySupposed::create();
        $part2->addMember($member4);
        $part2->addMember($member5);
        $raw_sequence->addHypothesisSupposed($part2);
        print_r("\n".$member4->getSlovo()->getText().' '.$member5->getSlovo()->getText()."\n"."\n");


        $member0=$raw_sequence->getMemberByPosition(0);
        $member1=$raw_sequence->getMemberByPosition(1);
        $member2=$raw_sequence->getMemberByPosition(2);
        $member3=$raw_sequence->getMemberByPosition(3);
        $member5=$raw_sequence->getMemberByPosition(5);
        $member6=$raw_sequence->getMemberByPosition(6);
        $member7=$raw_sequence->getMemberByPosition(7);
        $member8=$raw_sequence->getMemberByPosition(8);
        $member9=$raw_sequence->getMemberByPosition(9);
        $part_end=\Aot\Sviaz\HomogeneitySupposed::create();
        $part_end->addMember($member0);
        $part_end->addMember($member1);
        $part_end->addMember($member2);
        $part_end->addMember($member3);
        $part_end->addMember($member5);
        $part_end->addMember($member6);
        $part_end->addMember($member7);
        $part_end->addMember($member8);
        $part_end->addMember($member9);
        $raw_sequence->addHypothesisSupposed($part_end);
        */
        return $raw_sequence;
    }
}