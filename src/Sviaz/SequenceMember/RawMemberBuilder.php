<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 4:00
 */

namespace Aot\Sviaz\SequenceMember;


class RawMemberBuilder
{
    const MAX_MEMORY_USAGE = 2e9;
    /**
     * MemberBuilder constructor.
     */
    protected function __construct()
    {

    }

    /**
     * @return RawMemberBuilder
     */
    public static function create()
    {
        return new static();
    }

    public static $for_destructor_of_judy = [];

    /**
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @return \Aot\Sviaz\Sequence[]
     */
    public function getRawSequences(\Aot\Text\NormalizedMatrix $normalized_matrix)
    {
        $normalized_matrix->recreateMatrix(\Aot\Sviaz\SequenceMember\Render::create());
        $normalized_matrix->build();

        $sequences = [];
        $tmpl = \Aot\Sviaz\Sequence::create();
        foreach ($normalized_matrix->storage as $array) {

            $sequences[] = $sequence = clone($tmpl);
            static::$for_destructor_of_judy[] = $sequence;
            foreach ($array as $member) {
                $sequence[] = $member;
            }
        }

        if (memory_get_usage(true) > static::MAX_MEMORY_USAGE) {
            static::$for_destructor_of_judy = [];
        }

        return $sequences;
    }

}