<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 15:54
 */

namespace Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class NotEq extends Base
{
    /**
     * @param MorphologyBase $left
     * @param MorphologyBase $right
     * @return boolean
     */
    public function match(MorphologyBase $left, MorphologyBase $right)
    {
        throw new \RuntimeException("неужели этим кто-то воспользовался :-) ");
        //return get_class($left) !== get_class($left);
    }
}