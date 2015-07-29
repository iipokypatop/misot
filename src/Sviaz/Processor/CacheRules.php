<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/07/15
 * Time: 11:47
 */

namespace Aot\Sviaz\Processor;


class CacheRules
{

    private $storage = [];

    public static function create()
    {
        return new static();
    }

    public function put($array_objects, $svyaz)
    {
        $hash = '';
        foreach ($array_objects as $object) {
            $hash .= spl_object_hash($object);
        }
        $this->storage[$hash] = $svyaz;
    }

    public function get($array_objects)
    {
        $hash = '';
        foreach ($array_objects as $object) {
            $hash .= spl_object_hash($object);

        }
        if (!empty($this->storage[$hash])) {
            return $this->storage[$hash];
        }
        return false;
    }
}