<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:30
 */

namespace Aot\Sviaz\Rule\AssertedMember\Checker;

abstract class Base
{
    abstract public function execute(
        \Aot\Sviaz\Sequence $sequence,
        \Aot\Sviaz\Rule\AssertedMember\Base $expected_member,
        \Aot\Sviaz\SequenceMember\Base $actual_member
    );
}