<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:54
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\Map;

class Eq extends Base
{
    /**
     * @param MorphologyBase $left
     * @param MorphologyBase $right
     * @return boolean
     */
    public function match(MorphologyBase $left, MorphologyBase $right)
    {
        if ($left === $right) {
            throw new \RuntimeException("wtf?!");
        }

        if (get_class($left) === get_class($right)) {
            return true;
        }

        foreach (Map::getEqClasses() as $morphology_id => $variants) {
            foreach ($variants as $variant) {
                if (in_array(get_class($left), $variant, true) && in_array(get_class($right), $variant, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}