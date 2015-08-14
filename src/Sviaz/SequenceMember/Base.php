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

    protected function __construct()
    {

    }

    public static function create()
    {
        return new static();
    }

    protected $id;

    public function getId()
    {
        if (null === $this->id) {
            $this->id = spl_object_hash($this);
        }
        return $this->id;
    }
}
