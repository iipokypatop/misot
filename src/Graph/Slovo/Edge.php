<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:21
 */

namespace Aot\Graph\Slovo;


class Edge extends \BaseGraph\Edge
{
    /** @var \Aot\Sviaz\Rule\Base */
    protected $rule;

    /**
     * @param Vertex $from
     * @param Vertex $to
     * @param \Aot\Sviaz\Rule\Base $rule
     * @return static
     */
    public static function create(
        Vertex $from,
        Vertex $to,
        \Aot\Sviaz\Rule\Base $rule
    ) {
        $obj = new static($from, $to);
        $obj->rule = $rule;
        return $obj;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule()
    {
        return $this->rule;
    }
}