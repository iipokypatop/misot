<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 04:06
 */

namespace Aot\RussianMorphology\ChastiRechi\Narechie\Morphology;


use Aot\RussianMorphology\ChastiRechi\MorphologyBase;


class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}