<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 03.12.2015
 * Time: 12:34
 */

namespace Aot\Sviaz\PostProcessors;

/**
 * @brief Класс-фильтр повторяющихся связей между двумя одинаковыми словами
 *
 * Class RemoveDuplicateOfSviaz
 * @package Aot\Sviaz\PostProcessors
 */
class RemoveDuplicateOfSviaz extends Base
{
    /**
     * @param \Aot\Sviaz\Sequence $sequence
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[] $sviazi
     */
    public function run(\Aot\Sviaz\Sequence $sequence, array $sviazi)
    {
        $pairs = [];
        foreach ($sviazi as $sviaz) {
            $candidate = [
                $sviaz->getMainSequenceMember(),
                $sviaz->getDependedSequenceMember()
            ];
            if (empty($pairs)) {
                $pairs [] = $candidate;
                continue;
            }
            foreach ($pairs as $pair) {
                if ($candidate === $pair) {
                    $sequence->removeSviaz($sviaz);
                    continue 2;
                }
                $pairs[] = $candidate;
            }
        }
    }
}