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
    public function run(\Aot\Sviaz\Sequence $raw_sequence)
    {
        /*
         * ТЕСТОВЫЕ ДАННЫЕ ДЛЯ ОДНОРОДНОСТЕЙ!!!
         *
        $member6=$raw_sequence->getMemberByPosition(1);
        $member7=$raw_sequence->getMemberByPosition(2);
        $part3=\Aot\Sviaz\HomogeneitySupposed::create();
        $part3->addMember($member6);
        $part3->addMember($member7);
        $raw_sequence->addHypothesisSupposed($part3);
        print_r("\n".$member6->getSlovo()->getText().' '.$member7->getSlovo()->getText()."\n");


        //$member1=$raw_sequence->getMemberByPosition(4);
        $member2=$raw_sequence->getMemberByPosition(6);
        $member3=$raw_sequence->getMemberByPosition(7);
        $member4=$raw_sequence->getMemberByPosition(7);
        $member5=$raw_sequence->getMemberByPosition(9);

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

        */
        return $raw_sequence;
    }
}