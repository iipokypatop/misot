<?php
/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 18.07.2016
 * Time: 17:42
 */

namespace Aot\Sviaz\Processors\AotGraph;


class SubConjunctionRegistry
{
    /**
     * @var string[]
     */
    protected static $conjunction = [
        'потому что',
        'чтобы',
        'когда',
        'если',
        'как',
        'что',
        'несмотря на то что',
    ];

    /**
     * @var string[]
     */
    public static $sub_conjunctions = [
        'sub_conj_1',
        'sub_conj_2',
        'sub_conj_3',
        'sub_conj_4',
        'sub_conj_5',
        'sub_conj_6',
        'sub_conj_7',
    ];

    /**
     * @param Link $link
     * @return string
     */
    public static function getSubConjunctionText(\Aot\Sviaz\Processors\AotGraph\Link $link)
    {
        $key = null;
        $sub_conjunctions = \Aot\Sviaz\Processors\AotGraph\SubConjunctionRegistry::$sub_conjunctions;
        foreach ($sub_conjunctions as $key => $sub_conjunction) {
            if ($sub_conjunction === $link->getNameOfLink()) {
                break;
            }
        }

        if (isset(static::$conjunction[$key])) {
            return static::$conjunction[$key];
        }

        throw new \Aot\Exception('Union not found ' . var_export($link->getNameOfLink(), true));
    }
    
}