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

    public static $for_destructor_of_judy = [];
    /**
     * @param $ob
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    public function build($ob)
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

    //protected $pre_cache = [];
    /**
     * @param \Aot\Text\NormalizedMatrix $normalized_matrix
     * @return \Aot\Sviaz\Sequence[]
     */
    public function getRawSequences(\Aot\Text\NormalizedMatrix $normalized_matrix)
    {
        $sequences = [];

        $tmpl = \Aot\Sviaz\Sequence::create();


        $normalized_matrix->recreateMatrix([$this, 'build']);

        $normalized_matrix->build();


        foreach ($normalized_matrix->storage as $array) {

            $sequences[] = $sequence = clone($tmpl);
            static::$for_destructor_of_judy[] = $sequence;
            foreach ($array as $member) {

                //$pre_hash = spl_object_hash($member);
                /*$pre_hash= $member->pre_hash;
                if (isset($this->pre_cache[$pre_hash])) {
                    $raw_member = $this->pre_cache[$pre_hash];
                } else {
                    $raw_member = $this->build($member);

                    $this->pre_cache[$pre_hash] = $raw_member;
                }*/

                //$raw_member = $this->build($member);

                //$sequence->append($raw_member);
                //$sequence[]=$raw_member;
                $sequence[]=$member;
            }
        }


        return $sequences;
    }




    public function getOneRawSequences(\Aot\Text\NormalizedMatrix $normalized_matrix)
    {
        $sequences = [];

        foreach ($normalized_matrix as $array) {

            $sequences[] = $sequence = \Aot\Sviaz\Sequence::create();

            foreach ($array as $member) {

                $raw_member = $this->build($member);

                $sequence->append($raw_member);
            }
            break;
        }

        return $sequences;
    }
}