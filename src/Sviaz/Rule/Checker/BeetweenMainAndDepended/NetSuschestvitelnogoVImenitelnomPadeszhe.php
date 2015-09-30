<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 07.07.2015
 * Time: 15:50
 */

namespace Aot\Sviaz\Rule\Checker\BeetweenMainAndDepended;


class NetSuschestvitelnogoVImenitelnomPadeszhe extends Base
{
    public function check(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, \Aot\Sviaz\Sequence $sequence)
    {
        $result = parent::check($main_candidate, $depended_candidate, $sequence);

        if (!$result) {
            return $result;
        }

        if ($main_candidate === $depended_candidate) {
            throw new \RuntimeException("wtf!?");
        }

        foreach ($sequence as $member) {
            // test not cycling
            $sequence->getPosition($main_candidate);
        }

        $main_position = $sequence->getPosition($main_candidate);
        if ($main_position === null) {
            throw new \RuntimeException("wtf!?");
        }

        $depended_position = $sequence->getPosition($depended_candidate);
        if ($depended_position === null) {
            throw new \RuntimeException("wtf!?");
        }

        if ($main_position < $depended_position) {
            for ($i = $main_position + 1; $i < $depended_position; $i++) {
                $result = $this->checkParams($sequence[$i]);
            }
        } elseif ($main_position > $depended_position) {
            for ($i = $depended_position + 1; $i < $main_position; $i++) {
                $result = $this->checkParams($sequence[$i]);
            }
        }

        return $result;
    }


    public function checkParams(\Aot\Sviaz\SequenceMember\Base $member)
    {
        $result = true;
        if ($member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            $slovo = $member->getSlovo();
            if ($slovo instanceof \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base) {
                if ($slovo->padeszh instanceof \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij) {
                    $result = false;
                }
            }
        }

        return $result;
    }
}