<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.10.2015
 * Time: 12:10
 */

namespace Aot\Graph\Slovo;

class Vertex extends \BaseGraph\Vertex
{
    /** @var  \Aot\RussianMorphology\Slovo */
    protected $slovo;

    public static function create(
        Graph $graph,
        \Aot\RussianMorphology\Slovo $slovo,
        \Aot\RussianMorphology\ChastiRechi\Predlog\Base $predlog = null
    ) {
        $obj = new static($graph, static::getNextId());
        $obj->slovo = $slovo;
        return $obj;
    }

    /**
     * @return \Aot\RussianMorphology\Slovo
     */
    public function getSlovo()
    {
        return $this->slovo;
    }
}