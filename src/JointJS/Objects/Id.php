<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 11.08.2015
 * Time: 14:58
 */

namespace Aot\JointJS\Objects;


class Id extends \Aot\JointJS\JSON
{
    /**
     * @return array
     */
    protected function getFieldForJsonSerialize()
    {
        return [
            'id'
        ];
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        assert(is_string($id));

        $this->set('id', $id);

        return $this;
    }

    public static function create()
    {
        return new static();
    }

}