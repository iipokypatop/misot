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
     * @param Link $link
     * @return string
     * @throws \Exception
     */
    public static function getSubConjunctionText(\Aot\Sviaz\Processors\AotGraph\Link $link)
    {
        $conjunction = [
            'потому что',
            'чтобы',
            'когда',
            'если',
            'как',
            'что',
            'несмотря на то что',
        ];
        
        $key = null;
        $sub_conjunctions = \Aot\Sviaz\Processors\AotGraph\SubConjunctionRegistry::getSubConjunctions();
        foreach ($sub_conjunctions as $key => $sub_conjunction) {
            if ($sub_conjunction === $link->getNameOfLink()) {
                break;
            }
        }

        if (isset($conjunction[$key])) {
            return $conjunction[$key];
        }

        throw new \Exception('Union not found ' . var_export($link->getNameOfLink(), true));
    }

    /**
     * @return string[]
     */
    public static function getSubConjunctions()
    {
        return [
            'sub_conj_1',
            'sub_conj_2',
            'sub_conj_3',
            'sub_conj_4',
            'sub_conj_5',
            'sub_conj_6',
            'sub_conj_7',
        ];
    }
}