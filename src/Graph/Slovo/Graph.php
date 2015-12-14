<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 015, 15.10.2015
 * Time: 18:12
 */

namespace Aot\Graph\Slovo;


class Graph extends \BaseGraph\Graph
{
    /**
     * @return \Aot\Graph\Slovo\Graph
     */
    public static function create()
    {
        $obj = new static();
        return $obj;
    }
}