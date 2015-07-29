<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 03.07.2015
 * Time: 13:15
 */

namespace Aot\Sviaz\Rule\AssertedMember\Checker;


class ChasticaNePeredSlovom extends Base
{
    const NE = 'не';

    public function check(
        \Aot\Sviaz\Sequence $sequence,
        \Aot\Sviaz\Rule\AssertedMember\Base $expected_member,
        \Aot\Sviaz\SequenceMember\Base $actual_member
    )
    {

        $prev = null;
        foreach ($sequence as $member) {

            /** @var $member \Aot\Sviaz\SequenceMember\Word\Base */
            /** @var $prev \Aot\Sviaz\SequenceMember\Word\Base */

            if ($prev === null) {
                $prev = $member;
                continue;
            }

            if ($member === $actual_member) {
                if ($prev->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Chastica\Base
                    && strtolower($prev->getSlovo()->getText()) === static::NE
                ) {
                    return true;
                }
            }

            $prev = $member;
        }

        return false;
    }
}