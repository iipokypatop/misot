<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:54
 */

namespace Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

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
            throw new \LogicException("Must not be equal");
        }

        $left_class = get_class($left);
        $right_class = get_class($right);

        if ($left_class === $right_class) {
            return true;
        }

        if ($left_class === \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\ClassNull::class) {
            return true;
        }

        if ($right_class === \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\ClassNull::class) {
            return true;
        }

//        foreach (MorphologyRegistry::getClasses() as $morphology_id => $variants) {
//            foreach ($variants as $variant) {
//                if (in_array($left_class, $variant, true) && in_array($right_class, $variant, true)) {
//                    return true;
//                }
//            }
//        }

        return isset(static::$map_of_comparisons_morphology[$left_class][$right_class]);
    }
}