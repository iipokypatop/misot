<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:21
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching;


abstract class Base
{
    abstract public function attempt(\Aot\Sviaz\SequenceMember\Base $actual_left, \Aot\Sviaz\SequenceMember\Base $actual_right);
}