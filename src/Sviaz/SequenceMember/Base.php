<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 13:02
 */

namespace Aot\Sviaz\SequenceMember;


use Aot\RussianMorphology\Slovo;

abstract class Base
{
    /**
     * Word constructor.
     * @param $slovo
     */
    protected function __construct(Slovo $slovo)
    {
        $this->slovo = $slovo;
    }


    /** @var  Slovo */
    protected $slovo;

    /**
     * @return Slovo
     */
    public function getSlovo()
    {
        return $this->slovo;
    }
}
