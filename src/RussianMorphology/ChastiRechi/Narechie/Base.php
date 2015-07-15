<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 04:05
 */

namespace Aot\RussianMorphology\ChastiRechi\Narechie;

use Aot\RussianMorphology\Slovo;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Narechie
 * @property Morphology\StepenSravneniya\Base $stepen_sravneniia
 */
class Base extends Slovo
{
    public function getMorphology()
    {
        return [
            'stepen_sravneniia' => Morphology\StepenSravneniya\Base::class,
        ];
    }

    /**
     * Narechie constructor.
     * @param $text
     * @param Morphology\StepenSravneniya\Base $stepen_sravneniia
     * @return static
     */
    public static function create(
        $text,
        Morphology\StepenSravneniya\Base $stepen_sravneniia
    )
    {
        $ob = new static($text);

        $ob->stepen_sravneniia = $stepen_sravneniia;

        return $ob;
    }
}