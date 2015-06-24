<?php

namespace RussianMorphology;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:05
 */
class Slovo
{
    protected static $children = [
        ChastiRechi\Suschestvitelnoe\Base::class,
        ChastiRechi\Prilagatelnoe\Base::class
    ];

    protected $morphology;

    protected $text;

    /**
     * Word constructor.
     * @param $text
     */
    protected function __construct($text)
    {
        assert(!empty($text));
        $this->text = $text;
    }
}