<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 14/07/15
 * Time: 18:07
 */

namespace Aot\RussianMorphology\ChastiRechi\Deeprichastie\Morphology;


use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}