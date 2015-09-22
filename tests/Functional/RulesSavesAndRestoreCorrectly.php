<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:25
 */

namespace AotTest\Functional\Text;


use Aot\Sviaz\Rule\Container;

class RulesSavesAndRestoreCorrectly extends \AotTest\AotDataStorage
{
    public function testRulesSavesAndRestoresFromDb()
    {
        /** @var  \Aot\Sviaz\Rule\Base[] $rules */
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

        foreach ($rules as $index => $rule) {

            $rule->persist();
            $rule->flush();

            foreach ($rule->getLinks() as $link) {
                $link->persist();
                $link->flush();
            }

            $rule_from_db = \Aot\Sviaz\Rule\Base::createByDao($rule->getDao());
            $this->assertEquals($rule, $rule_from_db);
        }
    }

    public function not_testLaunch()
    {

    }
}