<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:25
 */

namespace Aot\Sviaz\SequenceMember\Word;

use Aot\RussianMorphology\Slovo;
use Aot\Sviaz\SequenceMember\Base as SequenceMemberBase;

class Base extends SequenceMemberBase
{

    /**
     * Word constructor.
     * @param $slovo
     */
    protected function __construct(Slovo $slovo)
    {
        $this->slovo = $slovo;
    }

    public static function create(Slovo $slovo)
    {
        return new static($slovo);
    }
}