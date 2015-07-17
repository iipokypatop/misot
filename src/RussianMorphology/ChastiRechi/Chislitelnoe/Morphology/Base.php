<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 17:47
 */

namespace Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}