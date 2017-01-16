<?php


namespace Aot\Sviaz\Processors\AotGraph\SyntaxModelManager\PostProcessors;


class KeySentenceProcessing extends Base
{

    /**
     * @param \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[] $syntax_model
     * @return \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel[]
     */
    public function run(array $syntax_model)
    {
        $map_ks_points = [];
        foreach ($syntax_model as $id => $item) {
            $map_ks_points[$item->getKs()][$item->getKw()][$id] = $item;
        }

        if (count($map_ks_points) < 2) {
            return $syntax_model;
        }

        ksort($map_ks_points);

        /**
         * Алгоритм замены позиций
         * 0: 0 1 2 3 4
         * 1: 0 1 2 3 4 5 -> 5 6 7 8 9
         * 2: 0 1 2 -> 10 11 12
         */
        $max_position = max(array_keys($map_ks_points[0]));
        foreach ($map_ks_points as $sentence_id => $map_points) {
            if ($sentence_id === 0) {
                continue;
            }
            $new_pos = [];
            /** @var \WrapperAot\ModelNew\Convert\SentenceSpaceSPRel $point */
            foreach ($map_points as $points_in_kw) {
                foreach ($points_in_kw as $point) {
                    $point->kw += $max_position + 1;
                    $new_pos[$point->kw] = $point->kw;
                    $point->ks = 0;
                }
            }
            $max_position = max($new_pos);
        }

        return $syntax_model;
    }
}