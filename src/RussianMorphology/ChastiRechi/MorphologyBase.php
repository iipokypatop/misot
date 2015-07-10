<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 14:50
 */

namespace Aot\RussianMorphology\ChastiRechi;

abstract class MorphologyBase
{
    /**
     * @return static []
     */
    public static function getTreeChildrenNodes()
    {
        return [
            Suschestvitelnoe\Morphology\Base::class,
            //Prilagatelnoe\Morphology\Base::class,
            Prichastie\Morphology\Base::class,
            Narechie\Morphology\Base::class,
        ];
    }
}