<?php
/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 25.06.15
 * Time: 12:13
 */
namespace Aot\RussianMorphology\ChastiRechi\Infinitive\Morphology;

use Aot\RussianMorphology\ChastiRechi\MorphologyBase;


class Base extends MorphologyBase
{
    public static function create()
    {
        return new static();
    }
}