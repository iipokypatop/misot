<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:21
 */

namespace Aot\Graph\Slovo;


class Edge extends \Aot\Graph\Edge
{
    /** @var \Aot\Sviaz\Rule\Base */
    protected $rule;

    /** @var \Aot\RussianMorphology\ChastiRechi\Predlog\Base */
    protected $predlog;

    /**
     * @param Vertex $from
     * @param Vertex $to
     * @param \Aot\Sviaz\Rule\Base $rule
     * @param \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog
     * @return static
     */
    public static function create(
        Vertex $from,
        Vertex $to,
        \Aot\Sviaz\Rule\Base $rule,
        \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog = null
    ) {
        $obj = new static($from, $to);
        $obj->rule = $rule;
        $obj->predlog = $predlog;
        return $obj;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return \Aot\RussianMorphology\ChastiRechi\Predlog\Base
     */
    public function getPredlog()
    {
        return $this->predlog;
    }

}