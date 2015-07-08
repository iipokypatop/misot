<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 14:49
 */

namespace Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology;


use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}