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
     * @param int $key
     * @return string|\LogicException
     */
    public static function getSubConjunctionText($key) {
        assert(is_int($key));
        
        $conjunction =  [
            'потому что',
            'чтобы',
            'когда',
            'если',
            'как',
            'что',
            'несмотря на то что',
        ];
        $result = null;
        if (isset($key, $conjunction)) {
            return $conjunction[$key];
        }

        throw new \LogicException('Union not found');
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