<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 12:59
 */

namespace Sviaz\Rule;


class Rule
{
    /** @var  \Sviaz\SequenceMember\Base */
    protected $main;


    /** @var  \Sviaz\SequenceMember\Base */
    protected $depended;

    protected $link;
}