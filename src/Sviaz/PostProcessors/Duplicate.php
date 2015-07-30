<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 13:59
 */

namespace Aot\Sviaz\PostProcessors;


class Duplicate extends Base
{
    /** @var \Aot\ObjectRegistry */
    protected $registry;


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
            $directions[$main_hash][$depended_hash][] = $sviaz;
        }

        $sviazi_new = [];
        foreach ($directions as $main_hash => $depended_hash_array) {
            foreach ($depended_hash_array as $depended_hash => $multiple_directional_sviazi) {
                if (count($multiple_directional_sviazi) === 1) {
                    $sviazi_new[] = $multiple_directional_sviazi[0];
                } else {
                    $sviazi_new[] = $this->choose($multiple_directional_sviazi);
                }
            }
        }

        return $sviazi_new;
    }

    /**
     * @param array $multiple_directional_sviazi
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    protected function choose(array $multiple_directional_sviazi)
    {
        return $multiple_directional_sviazi[0];
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