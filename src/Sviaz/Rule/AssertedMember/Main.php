<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 17:11
 */

namespace Aot\Sviaz\Rule\AssertedMember;


class Main extends Base
{
    public function attempt(\Aot\Sviaz\SequenceMember\Base $actual)
    {
        if ($actual instanceof \Aot\Sviaz\SequenceMember\Word\Base) {

            if (null !== $this->getAssertedChastRechiClass()) {
                if (get_class($actual->getSlovo()) !== $this->getAssertedChastRechiClass()) {
                    return false;
                }
            }

            return true;
        }

        throw new \RuntimeException("unsupported sequence_member type");
    }
}