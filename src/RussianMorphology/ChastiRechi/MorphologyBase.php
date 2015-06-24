<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 14:50
 */

namespace RussianMorphology\ChastiRechi;



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
        ];
    }
}