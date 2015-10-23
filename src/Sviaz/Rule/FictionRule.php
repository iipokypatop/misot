<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 01.07.2015
 * Time: 12:59
 */

namespace Aot\Sviaz\Rule;



/**
 * Class Base
 * @property \SemanticPersistence\Entities\MisotEntities\RuleConfig $dao
 * @package Aot\Sviaz\Rule
 */
class FictionRule extends Base
{

    public static function create($main = null, $dep = null)
    {
        return new static(\Aot\Sviaz\Rule\AssertedMember\Main::create(), \Aot\Sviaz\Rule\AssertedMember\Depended::create());
    }
}

