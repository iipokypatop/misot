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

    protected $slovo;

    protected $dao;

    /**
     * @return Slovo
     */
    public function getSlovo()
    {
        return $this->slovo;
    }

    /**
     * @param Slovo $slovo
     */
    public function setSlovo(\Aot\RussianMorphology\Slovo $slovo)
    {
        $this->slovo = $slovo;
    }


    protected function __construct()
    {

    }

    /**
     * @param Slovo|null $slovo
     * @return static
     */
    public static function create(Slovo $slovo = null)
    {
        assert(!is_null($slovo));

        $ob = new static($slovo);

        $ob->slovo = $slovo;
        //$ob->dao =

        return $ob;
    }


    public static function createByDao($dao)
    {
        $ob = new static();

        $ob->dao = $dao;

        return $ob;

    }
}