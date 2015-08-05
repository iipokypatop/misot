<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.08.2015
 * Time: 13:19
 */

require $imports['ShowParseResult']['model'];


$processor = \Aot\Sviaz\Processor\Base::create();

$sviazi_container = $processor->go(
    $normalized_matrix,
    array_merge([
        \Aot\Sviaz\Rule\Container::getRule1(), \Aot\Sviaz\Rule\Container::getRule2(),  \Aot\Sviaz\Rule\Container::getRule4(), \Aot\Sviaz\Rule\Container::getRule5(), \Aot\Sviaz\Rule\Container::getRule6(), \Aot\Sviaz\Rule\Container::getRule7(), \Aot\Sviaz\Rule\Container::getRule8(), \Aot\Sviaz\Rule\Container::getRule9(), \Aot\Sviaz\Rule\Container::getRule10(), \Aot\Sviaz\Rule\Container::getRule11(), \Aot\Sviaz\Rule\Container::getRule12(), \Aot\Sviaz\Rule\Container::getRule13(), \Aot\Sviaz\Rule\Container::getRule14(), \Aot\Sviaz\Rule\Container::getRule17(), \Aot\Sviaz\Rule\Container::getRule15(),

        \Aot\Sviaz\Rule\Container::getRule_PerehGl_Susch(),
        \Aot\Sviaz\Rule\Container::getRule_LichnoeMest_Pril(),
        \Aot\Sviaz\Rule\Container::getRule_OtricMest_Gl(),
        \Aot\Sviaz\Rule\Container::getRule_OtricMest_Prich(),
        //\Aot\Sviaz\Rule\Container::getRule_UkazMest_Susch(),
    ],
        \Aot\Sviaz\Rule\Container::getRuleSuch1(),
        \Aot\Sviaz\Rule\Container::getRuleSuchPadej()
    )
);

