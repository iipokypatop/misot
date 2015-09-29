<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 22:16
 */

namespace Aot\Sviaz\Rule\Checker\DependedPosition;


use Aot\Sviaz\Sequence;

class RightBeforeMain extends Base
{
    public function check(\Aot\Sviaz\SequenceMember\Base $main_candidate, \Aot\Sviaz\SequenceMember\Base $depended_candidate, Sequence $sequence)
    {
        $result = parent::check($main_candidate, $depended_candidate, $sequence);

        if (!$result) {
            return $result;
        }

        $result = $this->getPosition($depended_candidate, $sequence) + 1 === $this->getPosition($main_candidate, $sequence);

        return $result;
    }
}