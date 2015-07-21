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

    public function put($array_objects)
    {
        $hash = '';
        foreach ($array_objects as $object) {
            $id_object = spl_object_hash($object);
            // $hash .= spl_object_hash($object);
            $this->storage[$id_object] = $object;
        }
    }

    public function get($array_objects)
    {
//        $hash = '';
        foreach ($array_objects as $object) {
            $id_object = spl_object_hash($object);

            // $hash .= spl_object_hash($object);
            if( empty($this->storage[$id_object]) ){
                return false;
            }
        }
//        if( empty($this->storage[$hash]) ){
//            return false;
//        }

        return true;
    }

    /*public function put($rule, $main_candidate, $depended_candidate)
    {
        $id_rule = spl_object_hash($rule);
        $id_main_candidate = spl_object_hash($main_candidate);
        $id_depended_candidate = spl_object_hash($depended_candidate);
        $this->storage[$id_rule] = $rule;
        $this->storage[$id_main_candidate] = $main_candidate;
        $this->storage[$id_depended_candidate] = $depended_candidate;
    }

    public function get($rule, $main_candidate, $depended_candidate)
    {
        $id_rule = spl_object_hash($rule);
        $id_main_candidate = spl_object_hash($main_candidate);
        $id_depended_candidate = spl_object_hash($depended_candidate);

        if (!empty($this->storage[$id_rule])
            && !empty($this->storage[$id_main_candidate])
            && !empty($this->storage[$id_depended_candidate])) {
            return true;
        }
        return false;
    }*/
}