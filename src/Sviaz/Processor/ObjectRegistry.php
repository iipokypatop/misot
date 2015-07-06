<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 3:39
 */

namespace Aot\Sviaz\Processor;


class ObjectRegistry
{
    protected $members = [];

    public static function create()
    {
        return new static();
    }

    public function registerMember($ob)
    {
        $hash = spl_object_hash($ob);

        if (!$this->exists($hash)) {
            $this->members[$hash] = $ob;
        }

        return $hash;
    }

    public function clean()
    {
        $this->members = [];
    }

    protected function exists($hash)
    {
        return !empty($this->members[$hash]);
    }

    public function getMemberByHash($hash)
    {
        if (!$this->exists($hash)) {
            throw new \RuntimeException;
        }

        return $this->members[$hash];
    }
}