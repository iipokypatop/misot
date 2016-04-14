<?php
/**
 * Created by PhpStorm.
 * User: Angelina
 * Date: 10.07.15
 * Time: 11:52
 */

namespace Aot\Sviaz\Rule\Container;


class RulesList
{
    /**
     * @return \Aot\Sviaz\Rule\Base[]
     */
    public static function getRules()
    {
        /** @var \Aot\Sviaz\Rule\Container\Section\Base[] $sections */
        $sections = [
            $glagol = \Aot\Sviaz\Rule\Container\Section\Glagol::create()
        ];


        $rules = [];
        foreach ($sections as $section) {
            $rules = array_merge(
                $rules,
                $section->getRules()
            );
        }

        return $rules;

    }

}


































