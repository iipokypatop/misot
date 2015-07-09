<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 09/07/15
 * Time: 04:05
 */

namespace Aot\RussianMorphology\ChastiRechi\Narechie;

use Aot\RussianMorphology\Slovo;

class Base extends Slovo
{
    public function getMorphology()
    {
        return [
            'stepen_sravneniia' => Morphology\StepenSravneniia\Base::class,
        ];
    }

    public static function create(
        $text,
        Morphology\StepenSravneniia\Base $stepen_sravneniia
    )
    {
        $ob = new static($text);

        $ob->stepen_sravneniia = $stepen_sravneniia;

        return $ob;
    }
}