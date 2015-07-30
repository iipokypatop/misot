<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 4:00
 */

namespace Aot\Sviaz\SequenceMember;


use Aot\RussianMorphology\Slovo;


class RawMemberBuilder
{
    /** @var  \Aot\ObjectRegistry */
    protected $registry;
    /** @var \Aot\Sviaz\SequenceMember\Base[] */
    protected $store = [];

    /**
     * MemberBuilder constructor.
     */
    protected function __construct()
    {
        $this->registry = \Aot\ObjectRegistry::create();
    }

    public static function create()
    {
        return new static();
    }

    /**
     * @param $ob
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    protected function build($ob)
    {
        $id = $this->registry->registerMember($ob);

        if (!empty($this->store[$id])) {
            return $this->store[$id];
        }

        if ($ob instanceof \Aot\RussianSyntacsis\Punctuaciya\Base) {

            $this->store[$id] = Punctuation::create($ob);

        } elseif ($ob instanceof Slovo) {

            $this->store[$id] = Word\Base::create($ob);
        }

        if (!empty($this->store[$id])) {
            return $this->store[$id];
        }

        throw new \RuntimeException("unsupported object type ");
    }

    /**
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @return \Aot\Sviaz\Sequence[]
     */
    public function getRawSequences(\Aot\Text\NormalizedMatrix $normalized_matrix)
    {
        $sequences = [];

        foreach ($normalized_matrix as $array) {

            $sequences[] = $sequence = \Aot\Sviaz\Sequence::create();

            foreach ($array as $member) {

                $raw_member = $this->build($member);

                $sequence->append($raw_member);
            }
        }

        return $sequences;
    }
}