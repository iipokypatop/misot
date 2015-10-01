<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 024, 24.09.2015
 * Time: 17:04
 */

namespace AotTest\Functional;


class MainTest extends \AotTest\AotDataStorage
{
    public function testRun()
    {


        $this->markTestSkipped();

        $text = <<<TEXT
Гибель произошла в момент второго из самых опасных ритуалов хаджа.
Участвуя в нем, мусульмане обещают Аллаху приложить все усилия для изгнания бесов из своей жизни.
TEXT;

        $rules = array_merge([
            \Aot\Sviaz\Rule\Container::getRule1(),
            \Aot\Sviaz\Rule\Container::getRule2(),
            \Aot\Sviaz\Rule\Container::getRule4(),
            \Aot\Sviaz\Rule\Container::getRule5(),

            \Aot\Sviaz\Rule\Container::getRule6(),
            /*\Aot\Sviaz\Rule\Container::getRule7(),*/
            /*\Aot\Sviaz\Rule\Container::getRule8(),*/
            /*\Aot\Sviaz\Rule\Container::getRule9(),*/
            /*\Aot\Sviaz\Rule\Container::getRule10(),*/
            /*\Aot\Sviaz\Rule\Container::getRule11(),*/
            /*\Aot\Sviaz\Rule\Container::getRule12(),*/
            \Aot\Sviaz\Rule\Container::getRule13(),
            \Aot\Sviaz\Rule\Container::getRule14(),
            \Aot\Sviaz\Rule\Container::getRule17(),
            /*\Aot\Sviaz\Rule\Container::getRule15(),*/
            \Aot\Sviaz\Rule\Container::getRule_PerehGl_Susch(),
            \Aot\Sviaz\Rule\Container::getRule_LichnoeMest_Pril(),
            \Aot\Sviaz\Rule\Container::getRule_OtricMest_Gl(),
            \Aot\Sviaz\Rule\Container::getRule_OtricMest_Prich(),
        ],
            \Aot\Sviaz\Rule\Container::getRuleSuch1()
        /*\Aot\Sviaz\Rule\Container::getRuleSuchPadej()*/
        );

        $main = \Aot\Main::create();

        $main->run($text, $rules);
    }


}