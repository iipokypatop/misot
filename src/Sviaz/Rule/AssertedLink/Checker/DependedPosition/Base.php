<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 21:58
 */

namespace Aot\Sviaz\Rule\AssertedLink\Checker\DependedPosition;


abstract class Base extends \Aot\Sviaz\Rule\AssertedLink\Checker\Base
{
    public function getPosition(\Aot\Sviaz\SequenceMember\Base $member, \Aot\Sviaz\Sequence $sequence)
    {
        $position = $sequence->getPosition($member);

        if (null === $position) {
            throw new \RuntimeException("wtf?!");
        }

        return $position;
    }
}