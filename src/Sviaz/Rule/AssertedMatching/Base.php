<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:21
 */

namespace Aot\Sviaz\Rule\AssertedMatching;

use Aot\Persister;

abstract class Base
{
    use Persister;

    abstract public function attempt(\Aot\Sviaz\SequenceMember\Base $actual_left, \Aot\Sviaz\SequenceMember\Base $actual_right);
}