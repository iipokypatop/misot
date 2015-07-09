<?php

namespace Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;

class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}