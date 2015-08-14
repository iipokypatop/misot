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

    protected $id;

    public static function create()
    {
        $ob = new static();

        $ob->id = spl_object_hash($ob);

        return $ob;
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

    /**
     * @var \Aot\Sviaz\Podchinitrelnaya\Base[]
     */
    protected $sviazi = [];

    public function addSviaz(\Aot\Sviaz\Podchinitrelnaya\Base $sviaz)
    {
        $this->sviazi[] = $sviaz;
    }

    /**
     * @return Podchinitrelnaya\Base[]
     */
    public function getSviazi()
    {
        return $this->sviazi;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}