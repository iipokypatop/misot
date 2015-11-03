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



        // todo сделать данный метод!

        $member6=$raw_sequence->offsetGet(1);
        $member7=$raw_sequence->offsetGet(2);
        $part3 = \Aot\Sviaz\Homogeneity\HomogeneitySupposed::create(
            [
                $member6,
                $member7
            ],
            $raw_sequence
        );
        $raw_sequence->addHypothesisSupposed($part3);


        return $raw_sequence;
    }


}