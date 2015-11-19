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
        $sequences = [];

        $tmpl = \Aot\Sviaz\Sequence::create();

        $renderer = \Aot\Sviaz\SequenceMember\Render::create();
        $normalized_matrix->recreateMatrix($renderer);

        $normalized_matrix->build();

        foreach ($normalized_matrix->storage as $array) {

            $sequences[] = $sequence = clone($tmpl);
            static::$for_destructor_of_judy[] = $sequence;
            foreach ($array as $member) {
                $sequence[] = $member;
            }
        }

        if (memory_get_usage(true) > 2000000000) {
            static::$for_destructor_of_judy = [];
        }

        return $sequences;
    }

}