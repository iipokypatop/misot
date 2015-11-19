<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 29.07.2015
 * Time: 16:06
 */

namespace Aot\Sviaz\Podchinitrelnaya;


class Factory
{
    protected static $instances = null;

    protected function __construct()
    {
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (empty(static::$instances)) {
            static::$instances = new static;
        }

        return static::$instances;
    }

    /**
     * @param \Aot\Sviaz\SequenceMember\Word\Base $main_sequence_member
     * @param \Aot\Sviaz\SequenceMember\Word\Base $depended_sequence_member
     * @param \Aot\Sviaz\Rule\Base $rule
     * @param \Aot\Sviaz\Sequence $sequence
     * @return Base
     */
    public function build(
        \Aot\Sviaz\SequenceMember\Word\Base $main_sequence_member,
        \Aot\Sviaz\SequenceMember\Word\Base $depended_sequence_member,
        \Aot\Sviaz\Rule\Base $rule,
        \Aot\Sviaz\Sequence $sequence
    )
    {
        $type_class = $rule->getTypeClass();

        if ($type_class === \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::class) {

            return \Aot\Sviaz\Podchinitrelnaya\Soglasovanie::create(
                $main_sequence_member,
                $depended_sequence_member,
                $rule,
                $sequence
            );

        } else if ($type_class === \Aot\Sviaz\Podchinitrelnaya\Upravlenie::class) {

            return \Aot\Sviaz\Podchinitrelnaya\Upravlenie::create(
                $main_sequence_member,
                $depended_sequence_member,
                $rule,
                $sequence
            );

        } else if ($type_class === \Aot\Sviaz\Podchinitrelnaya\Primikanie::class) {

            return \Aot\Sviaz\Podchinitrelnaya\Primikanie::create(
                $main_sequence_member,
                $depended_sequence_member,
                $rule,
                $sequence
            );

        } else if ($type_class === \Aot\Sviaz\Podchinitrelnaya\Base::class) {

            return \Aot\Sviaz\Podchinitrelnaya\Base::create(
                $main_sequence_member,
                $depended_sequence_member,
                $rule,
                $sequence
            );
        }

        throw new \LogicException("unsupported Sviaz class " . var_export($type_class, 1));
    }
}