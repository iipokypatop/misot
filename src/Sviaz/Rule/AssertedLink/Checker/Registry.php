<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 14:19
 */

namespace Aot\Sviaz\Rule\AssertedLink\Checker;


use Aot\Sviaz\Rule\AssertedLink\Checker\BeetweenMainAndDepended\NetSuschestvitelnogoVImenitelnomPadeszhe;

class Registry
{
    const DependedAfterMain = 1;
    const DependedBeforeMain = 2;
    const DependedRightBeforeMain = 3;
    const DependedRightAfterMain = 4;

    const NetSuschestvitelnogoVImenitelnomPadeszhe = 10;

    public static function getClasses()
    {
        return [
            static::NetSuschestvitelnogoVImenitelnomPadeszhe => NetSuschestvitelnogoVImenitelnomPadeszhe::class,
            static::DependedAfterMain => \Aot\Sviaz\Rule\AssertedLink\Checker\DependedPosition\AfterMain::class,
            static::DependedBeforeMain => \Aot\Sviaz\Rule\AssertedLink\Checker\DependedPosition\BeforeMain::class,
            static::DependedRightBeforeMain => \Aot\Sviaz\Rule\AssertedLink\Checker\DependedPosition\RightBeforeMain::class,
            static::DependedRightAfterMain => \Aot\Sviaz\Rule\AssertedLink\Checker\DependedPosition\RightAfterMain::class,
        ];
    }

    /**
     * @param int $id
     * @param array $args
     * @return \Aot\Sviaz\Rule\AssertedLink\Checker\Base|null
     */
    public static function getObjectById($id, array $args = [])
    {
        assert(is_int($id));

        if (empty(static::getClasses()[$id])) {
            throw new \RuntimeException("unsupported id = " . var_export($id, 1));
        }

        return forward_static_call_array([static::getClasses()[$id], 'create'], $args);
    }
}