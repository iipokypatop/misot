<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.11.2015
 * Time: 10:50
 */

namespace Aot\Sviaz\SequenceMember;


class Render
{
    /** @var  \Aot\ObjectRegistry */
    protected $registry;

    /**
     * @return \Aot\ObjectRegistry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @return Base[]
     */
    public function getStore()
    {
        return $this->store;
    }

    /** @var \Aot\Sviaz\SequenceMember\Base[] */
    protected $store = [];


    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
        $this->registry = \Aot\ObjectRegistry::create();
    }

    /**
     * @param \Aot\Unit $ob
     * @return \Aot\Sviaz\SequenceMember\Base
     */
    public function run(\Aot\Unit $ob)
    {
        $id = $this->getRegistry()->registerMember($ob);
        $store = $this->getStore();
        if (!empty($store[$id])) {
            return $store[$id];
        }

        if ($ob instanceof \Aot\RussianSyntacsis\Punctuaciya\Base) {

            $store[$id] = Punctuation::create($ob);

        } elseif ($ob instanceof \Aot\RussianMorphology\Slovo) {

            $store[$id] = Word\Base::create($ob);
        }

        if (!empty($store[$id])) {
            return $store[$id];
        }

        throw new \RuntimeException("unsupported object type ");
    }

}