<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:09
 */

namespace Aot\Sviaz;


class Sequence extends \ArrayObject
{

    public static function create()
    {
        return new static;
    }

    public function getPosition(\Aot\Sviaz\SequenceMember\Base $search)
    {
        foreach ($this as $index => $member) {
            if ($search === $member) {
                return $index;
            }
        }

        return null;
    }
}