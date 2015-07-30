<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 13:59
 */

namespace Aot\Sviaz\PostProcessors;


class Bidirectional extends Base
{
    /** @var \Aot\ObjectRegistry */
    protected $registry;

    /**
     * Bidirectional constructor.
     */
    public function __construct()
    {
        $this->registry = \Aot\ObjectRegistry::create();
    }

    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     * @return \Aot\Sviaz\Podchinitrelnaya\Base[]|void
     */
    public function run(array $sviazi)
    {
        $directions = [];
        foreach ($sviazi as $sviaz) {
            $main_hash = $this->registry->registerMember($sviaz->getMainSequenceMember());
            $depended_hash = $this->registry->registerMember($sviaz->getDependedSequenceMember());
            $directions[$main_hash][] = $depended_hash;
        }

        $bidirections = [];
        foreach ($sviazi as $sviaz) {
            $main_hash = $this->registry->registerMember($sviaz->getMainSequenceMember());
            $depended_hash = $this->registry->registerMember($sviaz->getDependedSequenceMember());
            if (
                !empty($directions[$depended_hash])
                && in_array($main_hash, $directions[$depended_hash], true)
            ) {
                $bidirections[$this->getKey($main_hash, $depended_hash)][] = $sviaz;
            }

        }

        foreach ($bidirections as $bidirection) {
            if (count($bidirection) > 1) {
                $this->alertUser($bidirection);
            }
        }
    }

    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $bidirections
     */
    protected function alertUser($bidirections)
    {

    }

    /**
     * @param mixed $ob
     * @return string
     */
    protected function hash($ob)
    {
        return spl_object_hash($ob);
    }


    protected function getKey($str1, $str2)
    {
        return $str1 > $str2 ? $str1 . $str2 : $str2 . $str1;
    }
}