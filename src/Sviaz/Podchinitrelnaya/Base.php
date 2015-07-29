<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 14:27
 */

namespace Aot\Sviaz\Podchinitrelnaya;


class Base
{
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $main_sequence_member;
    /** @var  \Aot\Sviaz\SequenceMember\Base */
    protected $depended_sequence_member;
    /** @var \Aot\Sviaz\Rule\Base */
    protected $rule;
}