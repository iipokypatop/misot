<?php
/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 25.06.15
 * Time: 12:13
 */
namespace RussianMorphology\ChastiRechi\Glagol\Morphology;

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
            Litso\Base::class,
            Naklonenie\Base::class,
            Perehodnost\Base::class,
            Rod\Base::class,
            Spryazhenie\Base::class,
            Vid\Base::class,
            Vozvratnost\Base::class,
            Vremya\Base::class,
            Zalog\Base::class
        ];
    }
}
