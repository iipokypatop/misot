<?php

/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 25.06.15
 * Time: 12:13
 */

namespace Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;


class Base extends MorphologyBase
{
    /**
     * @return static []
     */
    public static function getTreeChildrenNodes()
    {
        return [
            Chislo\Base::class,
            Forma\Base::class,
            Padeszh\Base::class,
            Razriad\Base::class,
            Rod\Base::class,
            StepenSravneniia\Base::class,
        ];
    }
}
