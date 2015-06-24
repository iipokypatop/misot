<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 14:49
 */

namespace RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology;


use RussianMorphology\ChastiRechi\MorphologyBase;

class Base extends MorphologyBase
{
    /**
     * @return static []
     */
    public static function getTreeChildrenNodes()
    {
        return [
            Chislo\Base::class,
            Naritcatelnost\Base::class,
            Odushevlyonnost\Base::class,
            Padeszh\Base::class,
            Rod\Base::class,
            Sklonenie\Base::class,
        ];
    }
}